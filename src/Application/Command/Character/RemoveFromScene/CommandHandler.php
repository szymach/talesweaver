<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Character\RemoveFromScene;

use Talesweaver\Application\Bus\CommandHandlerInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $scene = $command->getScene();
        $character = $command->getCharacter();

        $scene->removeCharacter($character);
        $character->removeScene($scene);
    }
}
