<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Integration\Symfony\Pagination\Book\BookPaginator;

class ListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var BookPaginator
     */
    private $pagination;

    public function __construct(ResponseFactoryInterface $responseFactory, BookPaginator $pagination)
    {
        $this->responseFactory = $responseFactory;
        $this->pagination = $pagination;
    }

    public function __invoke(int $page): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'book/list.html.twig',
            ['books' => $this->pagination->getResults($page)]
        );
    }
}
