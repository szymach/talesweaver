<?php

namespace AppBundle\Character\Edit;

use AppBundle\Entity\Character;

class DTO
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    public function __construct(Character $character)
    {
        $this->name = $character->getName();
        $this->description = $character->getDescription();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(?string $name) : ?string
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(?string $description) : ?string
    {
        $this->description = $description;
    }
}
