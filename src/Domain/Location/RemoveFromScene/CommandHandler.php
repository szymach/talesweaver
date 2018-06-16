<?php

declare(strict_types=1);

namespace Domain\Location\RemoveFromScene;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $scene = $command->getScene();
        $location = $command->getLocation();

        $scene->removeLocation($location);
        $location->removeScene($scene);
    }
}
