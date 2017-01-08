<?php

namespace AppBundle\Pagination\Aggregate;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Scene\LocationPaginator as ForScenePaginator;

class LocationAggregate
{
    /**
     * @var ForScenePaginator
     */
    private $forScenePaginator;

    public function __construct(ForScenePaginator $forScenePaginator)
    {
        $this->forScenePaginator = $forScenePaginator;
    }

    /**
     * @param Scene $forScene
     * @return Pagerfanta
     */
    public function getForScene(Scene $scene)
    {
        return $this->forScenePaginator->getForSceneResults($scene, $page);
    }
}
