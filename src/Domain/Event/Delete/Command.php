<?php

namespace Domain\Event\Delete;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;
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

    public function __construct(Event $event)
    {
        $this->id = $event->getId();
        $this->createdBy = $event->getCreatedBy()->getId();
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
