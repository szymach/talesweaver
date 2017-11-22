<?php

declare(strict_types=1);

namespace Domain\Chapter\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->perform();
    }
}
