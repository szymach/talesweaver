<?php

namespace AppBundle\Book\Create;

class Command
{
    /**
     * @var DTO
     */
    private $dto;

    public function __construct(DTO $dto)
    {
        $this->dto = $dto;
    }

    public function getData(): DTO
    {
        return $this->dto;
    }
}
