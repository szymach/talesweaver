<?php

namespace Domain\Event\Edit;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements UserAccessInterface
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var DTO
     */
    private $dto;

    public function __construct(Event $event, DTO $dto)
    {
        $this->event = $event;
        $this->dto = $dto;
    }

    public function perform() : void
    {
        $this->event->edit($this->dto);
    }

    public function isAllowed(User $user): bool
    {
        return $this->event->getCreatedBy()->getId() === $user->getId();
    }
}