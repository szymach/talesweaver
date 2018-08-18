<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Pagination\Location;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\LocationRepository;

class LocationPaginator
{
    /**
     * @var LocationRepository
     */
    private $repository;

    public function __construct(LocationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(Scene $scene, int $page = 1, int $maxPerPage = 3): Pagerfanta
    {
        $pager = new Pagerfanta(new ArrayAdapter($this->repository->findForScene($scene)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
