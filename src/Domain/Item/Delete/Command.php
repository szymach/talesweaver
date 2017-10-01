<?php

namespace Domain\Item\Delete;

use AppBundle\Entity\Item;
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

    public function __construct(Item $item)
    {
        $this->id = $item->getId();
        $this->createdBy = $item->getCreatedBy()->getId();
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
