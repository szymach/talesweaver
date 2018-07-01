<?php

declare(strict_types=1);

namespace Application\Event\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->perform();
    }
}