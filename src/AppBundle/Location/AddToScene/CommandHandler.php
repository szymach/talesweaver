<?php

namespace AppBundle\Location\AddToScene;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
