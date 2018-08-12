<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Delete;

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
        $this->items->remove($command->getId());
    }
}
