<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event\Edit;

use Talesweaver\Domain\ValueObject\ShortText;

class CommandHandler
{
    public function handle(Command $command): void
    {
        $command->getEvent()->edit(
            new ShortText($command->getData()->getName()),
            $command->getData()->getModel(),
            $command->getData()->getScene()
        );
    }
}
