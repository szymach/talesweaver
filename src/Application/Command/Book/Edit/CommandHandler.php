<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Edit;

use Talesweaver\Application\Bus\CommandHandlerInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $command->getBook()->edit($command->getTitle(), $command->getDescription());
    }
}
