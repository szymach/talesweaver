<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Repository\SceneRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class ScenePaginator
{
    /**
     * @var SceneRepository
     */
    private $repository;

    public function __construct(SceneRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(int $page = 1, int $maxPerPage = 10) : Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->repository->createStandaloneQb()));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
