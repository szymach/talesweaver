<?php

namespace AppBundle\Location\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
