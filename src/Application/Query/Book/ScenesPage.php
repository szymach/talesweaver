<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Talesweaver\Domain\Book;

final class ScenesPage
{
    /**
     * @var Book
     */
    private $book;

    /**
     * @var int
     */
    private $page;

    public function __construct(Book $book, int $page)
    {
        $this->book = $book;
        $this->page = $page;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
