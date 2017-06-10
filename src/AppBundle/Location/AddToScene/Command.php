<?php

namespace AppBundle\Location\AddToScene;

use AppBundle\Entity\Location;
use AppBundle\Entity\Scene;

class Command
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var Location
     */
    private $location;

    public function __construct(Scene $scene, Location $location)
    {
        $this->scene = $scene;
        $this->location = $location;
    }

    public function perform()
    {
        $this->scene->addLocation($this->location);
    }
}
