<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Publication\ToggleVisibility;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command)
    {
        $command->getPublication()->toggleVisibility();
    }
}
