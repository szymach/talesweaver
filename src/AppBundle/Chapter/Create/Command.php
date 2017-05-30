<?php

namespace AppBundle\Chapter\Create;

use Ramsey\Uuid\Uuid;

class Command
{
    /**
     * @var type
     */
    private $id;

    /**
     * @var DTO
     */
    private $dto;

    public function __construct(Uuid $id, DTO $dto)
    {
        $this->id = $id;
        $this->dto = $dto;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getData(): DTO
    {
        return $this->dto;
    }
}
