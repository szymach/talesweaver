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
        $qb = $this->createTranslatableQueryBuilder('i');
        return $qb->leftJoin('i.scenes', 's')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF i.scenes',
                    's.chapter = :chapter'
                )
            )
            ->andWhere(':scene NOT MEMBER OF i.scenes')
            ->orWhere('s.id IS NULL')
            ->setParameter('scene', $scene)
            ->setParameter('chapter', $scene->getChapter())
        ;
    }
}
