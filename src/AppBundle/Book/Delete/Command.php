<?php

namespace AppBundle\Book\Delete;

use AppBundle\Entity\Book;
use Ramsey\Uuid\Uuid;

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

    public function getId(): Uuid
    {
        return $this->id;
    }
}
