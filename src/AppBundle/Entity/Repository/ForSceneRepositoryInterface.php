<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Piotr Szymaszek
 */
interface ForSceneRepositoryInterface
{
    /**
     * @param Scene $scene
     * @return QueryBuilder
     */
    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder;
}
