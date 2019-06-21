<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Domain\Book;

final class ChaptersPage
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var Book|null
     */
    private $book;

    public function __construct(int $page, ?Book $book)
    {
        $this->page = $page;
        $this->book = $book;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }
}
