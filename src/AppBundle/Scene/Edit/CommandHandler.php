<?php

namespace AppBundle\Scene\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
