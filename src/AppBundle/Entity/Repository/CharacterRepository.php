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
        $qb = $this->createTranslatableQueryBuilder('c');
        return $qb->leftJoin('c.scenes', 's')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF c.scenes',
                    's.chapter = :chapter'
                )
            )
            ->orWhere('s.id IS NULL')
            ->setParameter('scene', $scene)
            ->setParameter('chapter', $scene->getChapter())
        ;
    }

    public function createRelatedToScenesQueryBuilder(array $scenes) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('c')
            ->join('c.scenes', 's')
            ->andWhere(':scenes MEMBER OF c.scenes')
            ->setParameter('scenes', $scenes)
        ;
    }
}
