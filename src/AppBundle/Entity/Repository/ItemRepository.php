<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;

class ItemRepository extends TranslatableRepository
{
    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('i')
            ->andWhere(':scene MEMBER OF i.scenes')
            ->setParameter('scene', $scene)
        ;
    }

    public function createRelatedQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('i')
            ->join('i.scenes', 's')
            ->join('s.chapter', 'c')
            ->andWhere(':scene NOT MEMBER OF i.scenes')
            ->setParameter('scene', $scene)
        ;
    }
}
