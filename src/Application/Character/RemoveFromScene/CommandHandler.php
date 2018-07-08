<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\RemoveFromScene;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $scene = $command->getScene();
        $character = $command->getCharacter();

        $scene->removeCharacter($character);
        $character->removeScene($scene);
    }
}
