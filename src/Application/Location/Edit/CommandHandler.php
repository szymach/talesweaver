<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\Edit;

use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $description = $command->getData()->getDescription();
        $command->getLocation()->edit(
            new ShortText($command->getData()->getName()),
            null !== $description ? new LongText($description) : null,
            $command->getDto()->getAvatar()
        );
    }
}
