<?php

declare(strict_types=1);

namespace Application\Event\Edit;

use Application\Messages\EditionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Event;
use Domain\User;
use Application\Security\UserAccessInterface;

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
        $this->event->edit($this->dto->getName(), $this->dto->getModel(), $this->dto->getScene());
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
