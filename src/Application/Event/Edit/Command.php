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
    private $data;

    public function __construct(Event $event, DTO $data)
    {
        $this->event = $event;
        $this->data = $data;
    }

    public function isAllowed(Author $author): bool
    {
        return $this->event->getCreatedBy() === $author;
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('event');
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getData(): DTO
    {
        return $this->data;
    }
}
