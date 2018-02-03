<?php

declare(strict_types=1);

namespace Domain\Event\Edit;

use App\Bus\Messages\EditionSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use Domain\Entity\Event;
use Domain\Entity\User;
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
