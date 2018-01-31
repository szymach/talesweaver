<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Repository\Traits\ValidationTrait;
use App\Entity\Scene;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class EventRepository extends TranslatableRepository
{
    use ValidationTrait;

    public function createForSceneQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('e')
            ->where('e.scene = :scene')
            ->andWhere('e.createdBy = :user')
            ->setParameter('scene', $scene)
            ->setParameter('user', $user)
        ;
    }

    public function findInEventsById(User $user, UuidInterface $id): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.model LIKE :id')
            ->andWhere('e.createdBy = :user')
            ->setParameter('id', sprintf('%%"%s"%%', $id))
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }
}