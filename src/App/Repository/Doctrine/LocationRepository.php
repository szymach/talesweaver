<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Repository\Traits\ValidationTrait;
use Domain\Entity\Scene;
use Domain\Entity\User;
use Doctrine\ORM\QueryBuilder;

class LocationRepository extends TranslatableRepository
{
    use ValidationTrait;

    public function byCurrentUserForSceneQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('l')
            ->andWhere(':scene MEMBER OF l.scenes')
            ->andWhere('l.createdBy = :user')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scene', $scene)
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserRelatedQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        $qb = $this->createTranslatableQueryBuilder('l');
        return $qb->leftJoin('l.scenes', 's')
            ->where('l.createdBy = :user')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF l.scenes',
                    's.chapter = :chapter'
                )
            )
            ->andWhere(':scene NOT MEMBER OF l.scenes')
            ->orWhere('s.id IS NULL')
            ->orderBy('t.name', 'ASC')
            ->setParameter('chapter', $scene->getChapter())
            ->setParameter('scene', $scene)
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserRelatedToScenesQueryBuilder(User $user, array $scenes): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('l')
            ->join('l.scenes', 's')
            ->where('l.createdBy = :user')
            ->andWhere(':scenes MEMBER OF l.scenes')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scenes', $scenes)
            ->setParameter('user', $user)
        ;
    }
}
