<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Character\CharactersPage;
use Talesweaver\Application\Query\Scene\ById;
use Talesweaver\Domain\Scene;

class ListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        QueryBus $queryBus,
        HtmlContent $htmlContent
    ) {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->getScene($request->getAttribute('id'));
        $page = $request->getAttribute('page');
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'scene\characters\list.html.twig',
                [
                    'characters' => $this->queryBus->query(new CharactersPage($scene, $page)),
                    'sceneId' => $scene->getId(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null,
                    'page' => $page
                ]
            )
        ]);
    }

    private function getScene(?string $id): Scene
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No id for scene');
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
