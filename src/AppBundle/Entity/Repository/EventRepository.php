<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;

class EventRepository extends TranslatableRepository
{
    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('e')
            ->andWhere('e.scene = :scene')
            ->setParameter('scene', $scene)
        ;
    }
}
