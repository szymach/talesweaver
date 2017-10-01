<?php

namespace Domain\Character\RemoveFromScene;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
