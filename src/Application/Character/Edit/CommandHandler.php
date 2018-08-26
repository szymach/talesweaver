<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->getCharacter()->edit(
            $command->getName(),
            $command->getDescription(),
            $command->getAvatar()
        );
    }
}
