<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Item\Delete;

use Talesweaver\Application\Bus\CommandHandlerInterface;
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
        $this->items->remove($command->getId());
    }
}
