<?php

namespace AppBundle\Character\Edit;

use AppBundle\Entity\Character;

class Command
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Character
     */
    private $character;

    public function __construct(DTO $dto, Character $Character)
    {
        $this->dto = $dto;
        $this->character = $Character;
    }

    public function perform()
    {
        $this->character->edit($this->dto);
    }
}
