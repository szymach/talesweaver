<?php

namespace AppBundle\Character\AddToScene;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
