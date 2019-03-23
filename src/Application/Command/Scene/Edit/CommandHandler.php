<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Edit;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $scene = $command->getScene();
        $chapter = $command->getChapter();

        $scene->edit($command->getTitle(), $command->getText(), $chapter);
        if (null === $chapter && null !== $scene->getChapter()) {
            $scene->getChapter()->removeScene($scene);
        }
    }
}
