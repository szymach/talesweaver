<?php

namespace AppBundle\Location\RemoveFromScene;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
