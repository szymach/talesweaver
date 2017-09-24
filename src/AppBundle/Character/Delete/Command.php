<?php

namespace AppBundle\Character\Delete;

use AppBundle\Entity\Character;
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

    public function __construct(Character $character)
    {
        $this->id = $character->getId();
        $this->createdBy = $character->getCreatedBy()->getId();
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
