<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->getItem()->edit(
            $command->getDto()->getName(),
            $command->getDto()->getDescription(),
            $command->getDto()->getAvatar()
        );
    }
}
