<?php

namespace AppBundle\Pagination;

use AppBundle\Entity\Repository\EventRepository;
use AppBundle\Entity\Scene;
use Pagerfanta\Pagerfanta;

/**
 * @property EventRepository $repository
 */
class EventPaginator extends Paginator
{
    public function __construct(EventRepository $repository)
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
}
