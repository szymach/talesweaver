<?php

namespace AppBundle\Pagination\Aggregate;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Scene\ItemPaginator as ForScenePaginator;

class ItemAggregate
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
    public function getForScene(Scene $scene, $page)
    {
        return $this->forScenePaginator->getForSceneResults($scene, $page);
    }

    /**
     * @param Scene $forScene
     * @return Pagerfanta
     */
    public function getRelated(Scene $scene, $page)
    {
        return $this->forScenePaginator->getRelatedResults($scene, $page);
    }
}
