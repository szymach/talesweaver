<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Chapter;

class DisplayController
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
        return $this->responseFactory->fromTemplate(
            'chapter/display.html.twig',
            ['chapter' => $this->getChapter($request->getAttribute('id'))]
        );
    }

    private function getChapter(?string $id): Chapter
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No chapter id!');
        }

        $uuid = Uuid::fromString($id);
        $chapter = $this->queryBus->query(new ById($uuid));
        if (false === $chapter instanceof Chapter
            || $this->authorContext->getAuthor() !== $chapter->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No chapter for id "%s"!', $uuid->toString()));
        }

        return $chapter;
    }
}
