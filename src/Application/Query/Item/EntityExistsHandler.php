<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Item;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Items;

class EntityExistsHandler implements QueryHandlerInterface
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function __invoke(EntityExists $query): bool
    {
        return $this->items->entityExists($query->getName(), $query->getId(), $query->getScene());
    }
}
