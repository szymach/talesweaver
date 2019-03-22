<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Create;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Event;
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
        $this->events->add(
            new Event(
                $command->getId(),
                $command->getName(),
                $command->getScene(),
                $command->getAuthor(),
                $command->getCharacters()
            )
        );
    }
}
