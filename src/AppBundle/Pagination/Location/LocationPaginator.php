<?php

namespace AppBundle\Pagination\Location;

use AppBundle\Repository\LocationRepository;
use AppBundle\Entity\Scene;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

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

    public function getResults(Scene $scene, int $page = 1, int $maxPerPage = 10) : Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->repository->createForSceneQueryBuilder($scene)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
