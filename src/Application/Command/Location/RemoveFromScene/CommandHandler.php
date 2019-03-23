<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\RemoveFromScene;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $scene = $command->getScene();
        $location = $command->getLocation();

        $scene->removeLocation($location);
        $location->removeScene($scene);
    }
}
