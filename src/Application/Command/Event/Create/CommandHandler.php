<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Create;

use Talesweaver\Domain\Event;
use Talesweaver\Domain\Events;

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
                $command->getName(),
                $command->getModel(),
                $command->getScene(),
                $command->getAuthor()
            )
        );
    }
}
