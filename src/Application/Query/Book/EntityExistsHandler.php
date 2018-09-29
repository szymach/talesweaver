<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Books;

class EntityExistsHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(EntityExists $query): bool
    {
        return $this->books->entityExists($query->getTitle(), $query->getId());
    }
}
