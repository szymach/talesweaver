<?php

declare(strict_types=1);

namespace Talesweaver\Application\Chapter\Edit;

use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $chapter = $command->getChapter();
        $title = new ShortText($command->getDto()->getTitle());
        $book = $command->getDto()->getBook();

        if (null === $book && null !== $chapter->getBook()) {
            $chapter->getBook()->removeChapter($chapter);
        }
        $chapter->edit($title, $book);
    }
}
