<?php

declare(strict_types=1);

namespace Application\Scene\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $scene = $command->getScene();
        $chapter = $command->getDto()->getChapter();
        if (null === $chapter && null !== $scene->getChapter()) {
            $scene->getChapter()->removeScene($scene);
        }

        $scene->edit(
            $command->getDto()->getTitle(),
            $command->getDto()->getText(),
            $chapter
        );
    }
}
