<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;

class CharacterRepository extends TranslatableRepository
{
    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('c')
            ->andWhere(':scene MEMBER OF c.scenes')
            ->setParameter('scene', $scene)
        ;
    }

    public function createRelatedQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('c')
            ->join('c.scenes', 's')
            ->join('s.chapter', 'ch')
            ->andWhere(':scene NOT MEMBER OF c.scenes')
            ->setParameter('scene', $scene)
        ;
    }
}
