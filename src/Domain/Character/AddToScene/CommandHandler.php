<?php

namespace Domain\Character\AddToScene;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
