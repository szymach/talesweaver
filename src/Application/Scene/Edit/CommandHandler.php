<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $scene = $command->getScene();
        $chapter = $command->getChapter();

        $scene->edit($command->getTitle(), $command->getText(), $chapter);
        if (null === $chapter && null !== $scene->getChapter()) {
            $scene->getChapter()->removeScene($scene);
        }
    }
}
