<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Edit;

use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    public function handle(Command $command)
    {
        $description = $command->getData()->getDescription();
        $avatar = $command->getData()->getAvatar();
        $command->getItem()->edit(
            new ShortText($command->getData()->getName()),
            null !== $description ? new LongText($description) : null,
            null !== $avatar ? new File($avatar) : null
        );
    }
}
