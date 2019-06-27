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

    /**
     * @var int
     */
    private $perPage;

    public function __construct(int $page, ?Book $book, int $perPage = 10)
    {
        $this->page = $page;
        $this->book = $book;
        $this->perPage = $perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
