<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Delete;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Events;

class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var Events
     */
    private $events;

    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function __invoke(Command $command): void
    {
        $this->events->remove($command->getId());
    }
}
