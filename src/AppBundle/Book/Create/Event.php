<?php

namespace AppBundle\Book\Create;

use AppBundle\Entity\Book;

class Event
{
    /**
     * @var DTO
     */
    private $dto;

    public function __construct(DTO $dto)
    {
        $this->dto = $dto;
    }

    public function getData(): Book
    {
        return new Book($this->dto->getTitle());
    }
}
