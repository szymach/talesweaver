<?php

declare(strict_types=1);

namespace Domain\Character\AddToScene;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $scene = $command->getScene();
        $character = $command->getCharacter();

        $scene->addCharacter($character);
        $character->addScene($scene);
    }
}
