<?php

declare(strict_types=1);

namespace Domain\Event\Edit;

use AppBundle\Bus\Messages\EditionSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
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

    public function perform(): void
    {
        $this->event->edit($this->dto);
    }

    public function isAllowed(User $user): bool
    {
        return $this->event->getCreatedBy()->getId() === $user->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('event');
    }
}
