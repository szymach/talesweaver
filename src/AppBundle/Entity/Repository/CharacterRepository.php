<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Repository\Traits\ValidationTrait;
use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;

class CharacterRepository extends TranslatableRepository
{
    use ValidationTrait;
    
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
            ->orWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF c.scenes',
                    ':chapter MEMBER OF c.chapters'
                )
            )
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
