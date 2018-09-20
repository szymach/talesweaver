<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Book\BooksPage;

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

    public function __construct(ResponseFactoryInterface $responseFactory, QueryBus $queryBus)
    {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
    }

    public function __invoke(int $page): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'book/list.html.twig',
            ['books' => $this->queryBus->query(new BooksPage($page))]
        );
    }
}
