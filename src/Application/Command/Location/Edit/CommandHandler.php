<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->getLocation()->edit(
            $command->getName(),
            $command->getDescription(),
            $command->getAvatar()
        );
    }
}
