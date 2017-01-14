<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Repository\ForSceneRepositoryInterface;
use AppBundle\Entity\Scene;
use AppBundle\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

abstract class ForScenePaginator extends Paginator implements ForScenePaginatorInterface
{
    /**
     * @var ForSceneRepositoryInterface
     */
    protected $repository;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function getForSceneResults(Scene $scene, $page = 1, $maxPerPage = 10)
    {
        $this->queryBuilder = $this->repository->createForSceneQueryBuilder($scene);
        return $this->getResults($page, $maxPerPage);
    }

    public function getRelatedResults(Scene $scene, $page = 1, $maxPerPage = 10)
    {
        $this->queryBuilder = $this->repository->createRelatedQueryBuilder($scene);
        return $this->getResults($page, $maxPerPage);
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->queryBuilder;
    }
}
