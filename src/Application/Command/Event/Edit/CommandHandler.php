<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->getEvent()->edit($command->getName(), $command->getModel());
    }
}
