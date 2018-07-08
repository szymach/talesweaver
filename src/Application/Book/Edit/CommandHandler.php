<?php

declare(strict_types=1);

namespace Talesweaver\Application\Book\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->perform();
    }
}
