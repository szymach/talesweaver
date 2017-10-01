<?php

namespace Domain\Location\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
