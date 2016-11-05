<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Repository\ItemRepository;

/**
 * @property ItemRepository $repository
 */
class ItemPaginator extends ForScenePaginator
{
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }
}
