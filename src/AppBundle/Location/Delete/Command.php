<?php

namespace AppBundle\Location\Delete;

use AppBundle\Entity\Location;
use AppBundle\Entity\User;
use AppBundle\Security\UserAccessInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements UserAccessInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var int
     */
    private $createdBy;

    public function __construct(Location $location)
    {
        $this->id = $location->getId();
        $this->createdBy = $location->getCreatedBy()->getId();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function isAllowed(User $user) : bool
    {
        return $user->getId() === $this->createdBy;
    }
}
