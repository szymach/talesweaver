<?php

namespace AppBundle\Book\Edit;

use AppBundle\Entity\Book;

class Event
{
    /**
     * @var Book
     */
    private $book;

    public function __construct(DTO $dto, Book $book)
    {
        $book->edit($dto);
        $this->book = $book;
    }

    public function getData(): Book
    {
        return $this->book;
    }
}
