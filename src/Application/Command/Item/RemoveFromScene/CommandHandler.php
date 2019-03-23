<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Item\RemoveFromScene;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $scene = $command->getScene();
        $item = $command->getItem();

        $scene->removeItem($item);
        $item->removeScene($scene);
    }
}
