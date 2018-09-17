<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Item\Create;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Items;

class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function __invoke(Command $command): void
    {
        $this->items->add(
            new Item(
                $command->getId(),
                $command->getScene(),
                $command->getName(),
                $command->getDescription(),
                $command->getAvatar(),
                $command->getAuthor()
            )
        );
    }
}
