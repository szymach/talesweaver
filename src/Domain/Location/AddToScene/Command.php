<?php

namespace Domain\Location\AddToScene;

use AppBundle\Entity\Location;
use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
use AppBundle\Security\UserAccessInterface;

class Command implements UserAccessInterface
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

    public function perform() : void
    {
        $this->scene->addLocation($this->location);
    }

    public function isAllowed(User $user) : bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->location->getCreatedBy()->getId()
            && $user->getId() === $this->location->getCreatedBy()->getId()
        ;
    }
}
