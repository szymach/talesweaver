<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;

final class ScenesPage
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
     * @var Chapter|null
     */
    private $chapter;

    /**
     * @var int
     */
    private $perPage;

    public function __construct(int $page, ?Book $book, ?Chapter $chapter, int $perPage = 10)
    {
        $this->page = $page;
        $this->book = $book;
        $this->chapter = $chapter;
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

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
