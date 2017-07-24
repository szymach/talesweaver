<?php

namespace AppBundle\Book\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
