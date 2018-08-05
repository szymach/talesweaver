<?php

declare(strict_types=1);

namespace Talesweaver\Application\Chapter\Edit;

use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $chapter = $command->getChapter();
        $title = new ShortText($command->getData()->getTitle());
        $book = $command->getData()->getBook();

        if (null === $book && null !== $chapter->getBook()) {
            $chapter->getBook()->removeChapter($chapter);
        }
        $chapter->edit($title, $book);
    }
}
