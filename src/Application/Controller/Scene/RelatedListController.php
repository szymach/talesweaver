<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Query\Chapter\ScenesPage;

final class RelatedListController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        ChapterResolver $chapterResolver,
        ApiResponseFactoryInterface $responseFactory,
        QueryBus $queryBus
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
    }

    /**
     * @TODO missing test case
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->list(
            'scene/tab/relatedScenes.html.twig',
            [
                'chapterId' => $chapter->getId(),
                'list' => $this->queryBus->query(new ScenesPage($chapter, $page)),
                'page' => $page
            ]
        );
    }
}
