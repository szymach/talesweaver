<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Edit;

use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $scene = $command->getScene();
        $chapter = $command->getData()->getChapter();
        if (null === $chapter && null !== $scene->getChapter()) {
            $scene->getChapter()->removeScene($scene);
        }

        $description = $command->getData()->getText();
        $scene->edit(
            new ShortText($command->getData()->getTitle()),
            null !== $description ? new LongText($description) : null,
            $chapter
        );
    }
}
