<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Item\AddToScene;

use Talesweaver\Application\Bus\CommandHandlerInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $scene = $command->getScene();
        $item = $command->getItem();

        $scene->addItem($item);
        $item->addScene($scene);
    }
}
