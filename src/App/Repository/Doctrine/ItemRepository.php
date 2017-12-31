<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Repository\Traits\ValidationTrait;
use App\Entity\Scene;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

class ItemRepository extends TranslatableRepository
{
    use ValidationTrait;

    public function byCurrentUserForSceneQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('i')
            ->where(':scene MEMBER OF i.scenes')
            ->andWhere('i.createdBy = :user')
            ->setParameter('scene', $scene)
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserRelatedQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        $qb = $this->createTranslatableQueryBuilder('i');
        return $qb->leftJoin('i.scenes', 's')
            ->where('i.createdBy = :user')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF i.scenes',
                    's.chapter = :chapter'
                )
            )
            ->andWhere(':scene NOT MEMBER OF i.scenes')
            ->orWhere('s.id IS NULL')
            ->setParameter('chapter', $scene->getChapter())
            ->setParameter('scene', $scene)
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserRelatedToScenesQueryBuilder(User $user, array $scenes): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('i')
            ->join('i.scenes', 's')
            ->where('i.createdBy = :user')
            ->andWhere(':scenes MEMBER OF i.scenes')
            ->setParameter('scenes', $scenes)
            ->setParameter('user', $user)
        ;
    }
}
