<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;

class CharacterRepository extends TranslatableRepository implements ForSceneRepositoryInterface
{
    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('c')
            ->andWhere(':scene MEMBER OF c.scenes')
            ->setParameter('scene', $scene)
        ;
    }
}
