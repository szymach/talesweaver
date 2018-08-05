<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Edit;

use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    public function handle(Command $command)
    {
        $description = $command->getDto()->getDescription();
        $command->getItem()->edit(
            new ShortText($command->getDto()->getName()),
            null !== $description ? new LongText($description) : null,
            $command->getDto()->getAvatar()
        );
    }
}
