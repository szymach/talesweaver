<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Books;

class BooksPageHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(BooksPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(new ArrayAdapter($this->books->findAll()));
        $pager->setMaxPerPage(3);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
