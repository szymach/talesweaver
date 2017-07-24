<?php

namespace AppBundle\Character\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
