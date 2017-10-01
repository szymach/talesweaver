<?php

namespace Domain\Scene\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
