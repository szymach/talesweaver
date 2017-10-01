<?php

namespace Domain\Book\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
