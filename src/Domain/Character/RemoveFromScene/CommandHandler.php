<?php

declare(strict_types=1);

namespace Domain\Character\RemoveFromScene;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->perform();
    }
}
