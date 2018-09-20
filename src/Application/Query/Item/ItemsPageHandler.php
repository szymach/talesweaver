<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Item;

use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Integration\Symfony\Pagination\Item\ItemPaginator;

class ItemsPageHandler implements QueryHandlerInterface
{
    /**
     * @var ItemPaginator
     */
    private $pagination;

    public function __construct(ItemPaginator $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke(ItemsPage $query): Pagerfanta
    {
        return $this->pagination->getResults($query->getScene(), $query->getPage());
    }
}
