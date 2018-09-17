<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\AddToScene;

use Talesweaver\Application\Bus\CommandHandlerInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $scene = $command->getScene();
        $location = $command->getLocation();

        $scene->addLocation($location);
        $location->addScene($scene);
    }
}
