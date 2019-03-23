<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Item;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Items;

final class ForSceneHandler implements QueryHandlerInterface
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function __invoke(ForScene $query): array
    {
        return $this->items->findForScene($query->getScene());
    }
}
