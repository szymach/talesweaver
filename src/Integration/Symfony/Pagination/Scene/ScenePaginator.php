<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Pagination\Scene;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Integration\Symfony\Repository\SceneRepository;

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

    public function getResults(int $page = 1, int $maxPerPage = 10): Pagerfanta
    {
        $pager = new Pagerfanta(new ArrayAdapter($this->repository->findStandalone()));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
