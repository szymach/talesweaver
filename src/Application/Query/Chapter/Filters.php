<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Domain\Book;

final class Filters
{
    /**
     * @var Book|null
     */
    private $book;

    public function __construct(?Book $book)
    {
        $this->book = $book;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }
}
