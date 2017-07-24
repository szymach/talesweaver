<?php

namespace AppBundle\Character\RemoveFromScene;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
