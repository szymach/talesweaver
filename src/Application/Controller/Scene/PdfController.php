<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Scene\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Scene;

class PdfController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        QueryBus $queryBus,
        AuthorContext $authorContext,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->getScene($request->getAttribute('id'));
        $filename = (string) $scene->getTitle();
        if (null !== $scene->getChapter()) {
            $filename = sprintf('%s_%s', (string) $scene->getChapter()->getTitle(), $filename);
        }
        if (null !== $scene->getBook()) {
            $filename = sprintf('%s_%s', (string) $scene->getBook()->getTitle(), $filename);
        }

        return $this->responseFactory->toPdf($filename, 'scene/display.html.twig', ['scene' => $scene], null);
    }

    private function getScene(?string $id): Scene
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No scene id!');
        }

        $uuid = Uuid::fromString($id);
        $scene = $this->queryBus->query(new ById($uuid));
        if (false === $scene instanceof Scene
            || $this->authorContext->getAuthor() !== $scene->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No scene for id "%s"!', $uuid->toString()));
        }

        return $scene;
    }
}
