<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
