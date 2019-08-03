<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\UpdatePositionMultiple;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class CommandHandler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        $items = $command->getItems();
        array_walk($items, function (DTO $dto): void {
            $dto->getChapter()->setPosition($dto->getPosition());
        });
    }
}
