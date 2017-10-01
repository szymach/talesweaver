<?php

namespace Domain\Character\Edit;

class CommandHandler
{
    public function handle(Command $command)
    {
        $command->perform();
    }
}
