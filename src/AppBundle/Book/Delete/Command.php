<?php

namespace AppBundle\Book\Delete;

use AppBundle\Entity\Book;
use Ramsey\Uuid\UuidInterface;

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

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
