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

    public function __construct(int $page, ?Book $book, ?Chapter $chapter)
    {
        $this->page = $page;
        $this->book = $book;
        $this->chapter = $chapter;
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
}
