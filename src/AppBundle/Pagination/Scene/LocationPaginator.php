<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Repository\LocationRepository;

/**
 * @property LocationRepository $repository
 */
class LocationPaginator extends ForScenePaginator
{
    public function __construct(LocationRepository $repository)
    {
        $this->repository = $repository;
    }
}
