<?php

declare(strict_types=1);

namespace Talesweaver\Application\Book\Edit;

use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $description = $command->getDto()->getDescription();
        $command->getBook()->edit(
            new ShortText($command->getDto()->getTitle()),
            null !== $description ? new LongText($description) : null
        );
    }
}
