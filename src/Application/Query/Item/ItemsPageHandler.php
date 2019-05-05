<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Item;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Items;

final class ItemsPageHandler implements QueryHandlerInterface
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function __invoke(ItemsPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter($this->items->findForScene($query->getScene()))
        );
        $pager->setMaxPerPage(9);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
