<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;

final class Filters
{
    /**
     * @var Book|null
     */
    private $book;

    /**
     * @var Chapter|null
     */
    private $chapter;

    public function __construct(?Book $book, ?Chapter $chapter)
    {
        $this->book = $book;
        $this->chapter = $chapter;
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
