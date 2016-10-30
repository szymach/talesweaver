<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Scene;

class ItemRepository extends TranslatableRepository
{
    public function getForScene(Scene $scene)
    {
        return $this->createTranslatableQueryBuilder('l')
            ->andWhere(':scene MEMBER OF l.scenes')
            ->setParameter('scene', $scene)
            ->getQuery()
            ->getResult()
        ;
    }
}
