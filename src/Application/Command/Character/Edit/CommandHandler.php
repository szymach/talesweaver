<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Character\Edit;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $command->getCharacter()->edit(
            $command->getName(),
            $command->getDescription(),
            $command->getAvatar()
        );
    }
}
