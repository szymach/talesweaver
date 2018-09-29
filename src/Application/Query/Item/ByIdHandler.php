<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Item;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Items;

class ByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function __invoke(ById $query): ?Item
    {
        return $this->items->find($query->getId());
    }
}
