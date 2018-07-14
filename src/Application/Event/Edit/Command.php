<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Command implements AuthorAccessInterface, MessageCommandInterface
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

    public function isAllowed(Author $author): bool
    {
        return $this->event->getCreatedBy()->getId() === $author->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('event');
    }
}
