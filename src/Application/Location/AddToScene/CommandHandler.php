<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\AddToScene;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $scene = $command->getScene();
        $location = $command->getLocation();

        $scene->addLocation($location);
        $location->addScene($scene);
    }
}
