<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ById;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Domain\Chapter;

class RelatedListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        QueryBus $queryBus
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->queryBus = $queryBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->getChapter($request->getAttribute('id'));
        $page = $request->getAttribute('page');
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'scene/related/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'chapterTitle' => $chapter->getTitle(),
                    'scenes' => $this->queryBus->query(new ScenesPage($chapter, $page)),
                    'page' => $page
                ]
            )
        ]);
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
