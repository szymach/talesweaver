<?php

namespace Domain\Event\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
