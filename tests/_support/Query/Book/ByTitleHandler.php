<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Query\Book;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Books;

class ByTitleHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(ByTitle $query): ?Book
    {
        return $this->books->findOneByTitle($query->getTitle());
    }
}
