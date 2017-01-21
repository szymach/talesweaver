<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;

class LocationRepository extends TranslatableRepository
{
    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('l')
            ->andWhere(':scene MEMBER OF l.scenes')
            ->setParameter('scene', $scene)
        ;
    }

    public function createRelatedQueryBuilder(Scene $scene) : QueryBuilder
    {
        $qb = $this->createTranslatableQueryBuilder('l');
        return $qb->leftJoin('l.scenes', 's')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF l.scenes',
                    's.chapter = :chapter'
                )
            )
            ->andWhere(':scene NOT MEMBER OF l.scenes')
            ->orWhere('s.id IS NULL')
            ->setParameter('scene', $scene)
            ->setParameter('chapter', $scene->getChapter())
        ;
    }

    public function createRelatedToScenesQueryBuilder(array $scenes) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('l')
            ->join('l.scenes', 's')
            ->andWhere(':scenes MEMBER OF l.scenes')
            ->setParameter('scenes', $scenes)
        ;
    }
}
