<?php

namespace AppBundle\Entity\Translation;

use AppBundle\Entity\Location;
use AppBundle\Entity\Traits\LocaleTrait;

class LocationTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Location
     */
    private $location;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setLocation(?Location $location) : void
    {
        $this->location = $location;
    }

    public function getLocation() : ?Location
    {
        return $this->location;
    }
}
