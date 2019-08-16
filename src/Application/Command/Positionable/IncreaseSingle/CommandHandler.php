<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Positionable\IncreaseSingle;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Application\Command\Positionable\PositionableRepositoryReducer;

final class CommandHandler implements CommandHandlerInterface
{
    use PositionableRepositoryReducer;

    public function __invoke(Command $command): void
    {
        $this->getRepository($command->getItem())->increasePosition($command->getItem());
    }
}
