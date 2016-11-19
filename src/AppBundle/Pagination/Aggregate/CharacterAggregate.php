<?php

namespace AppBundle\Pagination\Aggregate;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Scene\CharacterPaginator as ForScenePaginator;

class CharacterAggregate
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
    public function getForScene(Scene $forScene)
    {
        return $this->forScenePaginator->getForSceneResults($forScene);
    }
}
