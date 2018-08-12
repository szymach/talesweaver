<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event\Create;

use Talesweaver\Domain\Event;
use Talesweaver\Domain\Events;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    /**
     * @var Events
     */
    private $events;

    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function handle(Command $command): void
    {
        $this->events->add(
            new Event(
                $command->getId(),
                new ShortText($command->getData()->getName()),
                $command->getData()->getModel(),
                $command->getData()->getScene(),
                $command->getAuthor()
            )
        );
    }
}
