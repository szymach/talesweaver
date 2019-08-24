<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Books;

final class AllBooksHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(AllBooks $query): array
    {
        return $this->books->findAll();
    }
}
