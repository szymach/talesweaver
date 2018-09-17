<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Edit;

use JsonSerializable;
use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\ShortText;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var ShortText
     */
    private $name;

    /**
     * @var JsonSerializable
     */
    private $model;

    public function __construct(Event $event, ShortText $name, JsonSerializable $model)
    {
        $this->event = $event;
        $this->name = $name;
        $this->model = $model;
    }

    public function getName(): ShortText
    {
        return $this->name;
    }

    public function getModel(): JsonSerializable
    {
        return $this->model;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->event->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('event');
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}
