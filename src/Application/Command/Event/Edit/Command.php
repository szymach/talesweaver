<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAccessInterface, MessageCommandInterface
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
     * @var Character[]
     */
    private $characters;

    public function __construct(Event $event, ShortText $name, array $characters)
    {
        $this->event = $event;
        $this->name = $name;
        $this->characters = $characters;
    }

    public function getName(): ShortText
    {
        return $this->name;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function isAllowed(Author $author): bool
    {
        $charactersBelong = array_reduce(
            $this->characters,
            function (bool $accumulator, Character $character) use ($author): bool {
                if (false === $accumulator) {
                    return $accumulator;
                }

                return $author === $character->getCreatedBy();
            },
            true
        );

        return $author === $this->event->getCreatedBy() && true === $charactersBelong;
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('event');
    }
}
