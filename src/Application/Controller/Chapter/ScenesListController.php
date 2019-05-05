<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Query\Chapter\ScenesPage;

final class ScenesListController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        ChapterResolver $chapterResolver,
        QueryBus $queryBus,
        ApiResponseFactoryInterface $responseFactory
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->list(
            'chapter/tab/scenes.html.twig',
            [
                'chapterId' => $chapter->getId(),
                'scenes' => $this->queryBus->query(new ScenesPage($chapter, $page)),
                'page' => $page
            ]
        );
    }
}
