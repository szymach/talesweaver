<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ChaptersPage;
use Talesweaver\Application\Query\Chapter\Filters;

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
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        BookResolver $bookResolver,
        QueryBus $queryBus
    ) {
        $this->responseFactory = $responseFactory;
        $this->bookResolver = $bookResolver;
        $this->queryBus = $queryBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $page = (int) $request->getAttribute('page', 1);
        $book = $this->bookResolver->fromQueryFilter($request);
        return $this->responseFactory->fromTemplate(
            'chapter/list.html.twig',
            [
                'chapters' => $this->queryBus->query(new ChaptersPage($page, $book)),
                'filters' => $this->queryBus->query(new Filters($book)),
                'page' => $page
            ]
        );
    }
}
