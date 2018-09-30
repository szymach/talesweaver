<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Books;

class ByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(ById $query): ?Book
    {
        return $this->books->find($query->getId());
    }
}
