<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Create;

use Talesweaver\Domain\Item;
use Talesweaver\Domain\Items;

class CommandHandler
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function handle(Command $command): void
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
