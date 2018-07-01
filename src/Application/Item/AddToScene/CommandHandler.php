<?php

declare(strict_types=1);

namespace Application\Item\AddToScene;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $scene = $command->getScene();
        $item = $command->getItem();

        $scene->addItem($item);
        $item->addScene($scene);
    }
}