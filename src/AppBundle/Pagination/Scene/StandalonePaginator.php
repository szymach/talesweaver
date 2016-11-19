<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Repository\SceneRepository;
use AppBundle\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class StandalonePaginator extends Paginator
{
    /**
     * @var SceneRepository
     */
    private $repository;

    public function __construct(SceneRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->repository->createStandaloneQb();
    }
}
