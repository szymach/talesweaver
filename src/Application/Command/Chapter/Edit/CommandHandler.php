<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Edit;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $chapter = $command->getChapter();
        $book = $command->getBook();

        $chapter->edit($command->getTitle(), $book);
        if (null === $book && null !== $chapter->getBook()) {
            $chapter->getBook()->removeChapter($chapter);
        }
    }
}
