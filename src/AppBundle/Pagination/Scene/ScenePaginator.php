<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Repository\SceneRepository;
use AppBundle\Pagination\Paginator;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class ScenePaginator extends Paginator
{
    public function __construct(SceneRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStandalone(int $page = 1, int $maxPerPage = 10) : Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->repository->createStandaloneQb()));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
