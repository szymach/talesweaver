<?php

declare(strict_types=1);

namespace Domain\Scene\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->perform();
    }
}
