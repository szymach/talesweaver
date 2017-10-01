<?php

namespace Domain\Chapter\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
