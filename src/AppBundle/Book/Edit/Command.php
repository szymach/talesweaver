<?php

namespace AppBundle\Book\Edit;

use AppBundle\Entity\Book;

class Command
{
    public function __construct(DTO $dto, Book $book)
    {
        $book->edit($dto);
    }
}
