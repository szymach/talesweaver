<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
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
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ChapterResolver $chapterResolver,
        QueryBus $queryBus
    ) {
        $this->responseFactory = $responseFactory;
        $this->chapterResolver = $chapterResolver;
        $this->queryBus = $queryBus;
    }

    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $page = (int) $request->getAttribute('page', 1);
        $chapter = $this->chapterResolver->nullableFromQuery($request, 'chapter');
        return $this->responseFactory->fromTemplate(
            'scene/list.html.twig',
            [
                'scenes' => $this->queryBus->query(new ScenesPage($page, $chapter)),
                'filters' => $this->queryBus->query(new Filters($chapter)),
                'page' => $page
            ]
        );
    }
}
