<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Integration\Symfony\Pagination\Book\BookPaginator;

class BooksPageHandler implements QueryHandlerInterface
{
    /**
     * @var BookPaginator
     */
    private $pagination;

    public function __construct(BookPaginator $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke(BooksPage $query): Pagerfanta
    {
        return $this->pagination->getResults($query->getPage());
    }
}
