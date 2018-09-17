<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Character\AddToScene;

use Talesweaver\Application\Bus\CommandHandlerInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $scene = $command->getScene();
        $character = $command->getCharacter();

        $scene->addCharacter($character);
        $character->addScene($scene);
    }
}
