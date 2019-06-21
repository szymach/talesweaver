<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Scene\Filters;
use Talesweaver\Application\Query\Scene\ScenesPage;

final class ListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var BookResolver
     */
    private $bookResolver;

    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        BookResolver $bookResolver,
        ChapterResolver $chapterResolver,
        QueryBus $queryBus
    ) {
        $this->responseFactory = $responseFactory;
        $this->bookResolver = $bookResolver;
        $this->chapterResolver = $chapterResolver;
        $this->queryBus = $queryBus;
    }

    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $page = (int) $request->getAttribute('page', 1);
        $book = $this->bookResolver->nullableFromQuery($request, 'book');
        $chapter = $this->chapterResolver->nullableFromQuery($request, 'chapter');
        return $this->responseFactory->fromTemplate(
            'scene/list.html.twig',
            [
                'scenes' => $this->queryBus->query(new ScenesPage($page, $book, $chapter)),
                'filters' => $this->queryBus->query(new Filters($book, $chapter)),
                'page' => $page
            ]
        );
    }
}
