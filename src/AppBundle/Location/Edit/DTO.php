<?php

namespace AppBundle\Location\Edit;

use AppBundle\Entity\Location;

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

    public function __construct(Location $location)
    {
        $this->name = $location->getName();
        $this->description = $location->getDescription();
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
