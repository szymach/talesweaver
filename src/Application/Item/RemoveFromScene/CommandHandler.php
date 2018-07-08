<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\RemoveFromScene;

class CommandHandler
{
    public function handle(Command $command)
    {
        $scene = $command->getScene();
        $item = $command->getItem();

        $scene->removeItem($item);
        $item->removeScene($scene);
    }
}
