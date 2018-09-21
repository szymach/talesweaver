<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Item;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Items;

class RelatedPageHandler implements QueryHandlerInterface
{
    /**
     * @var Items
     */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function __invoke(RelatedPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter($this->items->findRelated($query->getScene()))
        );
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
