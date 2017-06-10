<?php

namespace AppBundle\Location\Edit;

use AppBundle\Entity\Location;

class Command
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Location
     */
    private $location;

    public function __construct(DTO $dto, Location $location)
    {
        $this->dto = $dto;
        $this->location = $location;
    }

    public function perform()
    {
        $this->location->edit($this->dto);
    }
}
