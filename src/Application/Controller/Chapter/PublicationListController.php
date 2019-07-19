<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Query\Chapter\PublicationsPage;

final class PublicationListController
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

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->list(
            'partial/publications.html.twig',
            [
                'publications' => $this->queryBus->query(new PublicationsPage($chapter, $page)),
                'createRoute' => 'chapter_publish',
                'createParameters' => ['id' => $chapter->getId()],
                'listRoute' => 'chapter_publication_list',
                'listParameters' => ['id' => $chapter->getId(), 'page' => $page],
            ]
        );
    }
}
