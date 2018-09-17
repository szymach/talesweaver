<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->getBook()->edit($command->getTitle(), $command->getDescription());
    }
}
