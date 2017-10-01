<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Traits\ValidationTrait;
use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class EventRepository extends TranslatableRepository
{
    use ValidationTrait;

    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('e')
            ->andWhere('e.scene = :scene')
            ->setParameter('scene', $scene)
        ;
    }

    public function findInEventsById(UuidInterface $id) : array
    {
        return $this->createQueryBuilder('e')
            ->where('e.model LIKE :id')
            ->setParameter('id', sprintf('%%"%s"%%', $id))
            ->getQuery()
            ->getResult()
        ;
    }
}
