<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Edit;

use Talesweaver\Application\Bus\CommandHandlerInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $command->getEvent()->edit($command->getName());
    }
}
