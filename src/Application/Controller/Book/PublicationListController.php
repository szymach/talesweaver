<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Query\Book\PublicationsPage;

final class PublicationListController
{
    /**
     * @var BookResolver
     */
    private $bookResolver;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        BookResolver $bookResolver,
        ApiResponseFactoryInterface $responseFactory,
        QueryBus $queryBus
    ) {
        $this->bookResolver = $bookResolver;
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->bookResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->list(
            'partial/publications.html.twig',
            [
                'publications' => $this->queryBus->query(new PublicationsPage($book, $page)),
                'createRoute' => 'book_publish',
                'createParameters' => ['id' => $book->getId()],
                'listRoute' => 'book_publication_list',
                'listParameters' => ['id' => $book->getId(), 'page' => $page],
            ]
        );
    }
}
