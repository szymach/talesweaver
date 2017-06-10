<?php

namespace AppBundle\Location\RemoveFromScene;

use AppBundle\Entity\Location;
use AppBundle\Entity\Scene;

class Command
{
    /**
     * @var Location
     */
    private $location;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene, Location $location)
    {
        $this->scene = $scene;
        $this->location = $location;
    }

    public function perform()
    {
        $this->scene->removeLocation($this->location);
    }
}
