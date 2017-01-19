<?php

namespace AppBundle\Pagination;

use AppBundle\Entity\Repository\LocationRepository;
use AppBundle\Entity\Scene;
use Pagerfanta\Pagerfanta;

/**
 * @property LocationRepository $repository
 */
class LocationPaginator extends Paginator
{
    public function __construct(LocationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Scene $scene
     * @param int $page
     * @return Pagerfanta
     */
    public function getForScene(Scene $scene, int $page) : Pagerfanta
    {
        return $this->getResults(
            $this->repository->createForSceneQueryBuilder($scene),
            $page
        );
    }

    public function getRelated(Scene $scene, int $page) : Pagerfanta
    {
        return $this->getResults(
            $this->repository->createRelatedQueryBuilder($scene),
            $page
        );
    }
}
