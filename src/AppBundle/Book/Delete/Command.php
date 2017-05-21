<?php

namespace AppBundle\Book\Delete;

use AppBundle\Entity\Book;

class Command
{
    /**
     * @var int
     */
    private $id;

    public function __construct(Book $book)
    {
        $this->id = $book->getId();
    }

    public function getId(): int
    {
        return $this->id;
    }
}
