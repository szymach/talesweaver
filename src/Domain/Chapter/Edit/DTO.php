<?php

declare(strict_types=1);

namespace Domain\Chapter\Edit;

use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Domain\Model\IdentityTrait;

class DTO
{
    use IdentityTrait;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Book
     */
    private $book;

    public function __construct(Chapter $chapter)
    {
        $this->id = $chapter->getId();
        $this->title = $chapter->getTitle();
        $this->book = $chapter->getBook();
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }
}
