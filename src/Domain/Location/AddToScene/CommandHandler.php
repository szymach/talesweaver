<?php

namespace Domain\Location\AddToScene;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
