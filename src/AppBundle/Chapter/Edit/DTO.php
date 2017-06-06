<?php

namespace AppBundle\Chapter\Edit;

use AppBundle\Entity\Chapter;

class DTO
{
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
        $this->title = $chapter->getTitle();
        $this->book = $chapter->getBook();
    }

    public function setTitle(?string $title)
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

    public function setBook(?Book $book)
    {
        $this->book = $book;
    }
}
