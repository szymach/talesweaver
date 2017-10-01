<?php

namespace Domain\Chapter\Edit;

use AppBundle\Entity\Book;
use AppBundle\Entity\Chapter;
use AppBundle\Traits\IdentityTrait;

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

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getBook() : ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book)
    {
        $this->book = $book;
    }
}
