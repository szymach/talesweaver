<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Item;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Items;

final class ForEventHandler implements QueryHandlerInterface
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $characters)
    {
        $this->items = $characters;
    }

    public function __invoke(ForEvent $query): array
    {
        return $this->items->findForEvent($query->getScene());
    }
}
