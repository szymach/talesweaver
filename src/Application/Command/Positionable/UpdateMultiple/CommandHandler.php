<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Positionable\UpdateMultiple;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $items = $command->getItems();
        array_walk($items, function (DTO $dto): void {
            $dto->getPositionable()->setPosition($dto->getPosition());
        });
    }
}
