<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Edit;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $chapter = $command->getChapter();
        $book = $command->getBook();

        $chapter->edit($command->getTitle(), $command->getPreface(), $book);
        if (null === $book && null !== $chapter->getBook()) {
            $chapter->getBook()->removeChapter($chapter);
        }
    }
}
