<?php

namespace AppBundle\Item\Edit;

use AppBundle\Entity\Item;

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

    public function __construct(Item $item)
    {
        $this->name = $item->getName();
        $this->description = $item->getDescription();
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }
}
