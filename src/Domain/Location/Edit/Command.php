<?php

namespace Domain\Location\Edit;

use AppBundle\Entity\Location;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements UserAccessInterface
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

    public function perform() : void
    {
        $this->location->edit($this->dto);
    }

    public function isAllowed(User $user) : bool
    {
        return $this->location->getCreatedBy()->getId() === $user->getId();
    }
}
