<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Delete;

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
        $this->events->remove($command->getId());
    }
}
