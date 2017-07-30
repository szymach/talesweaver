<?php

namespace AppBundle\Chapter\Create;

use AppBundle\Entity\Book;

class DTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var Book|null
     */
    private $book;

    public function __construct(Book $book = null)
    {
        $this->book = $book;
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
