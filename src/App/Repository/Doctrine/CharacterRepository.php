<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Repository\Traits\ValidationTrait;
use Doctrine\ORM\QueryBuilder;
use Domain\Entity\Scene;
use Domain\Entity\User;

class CharacterRepository extends TranslatableRepository
{
    use ValidationTrait;

    public function byCurrentUserForSceneQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('c')
            ->andWhere(':scene MEMBER OF c.scenes')
            ->andWhere('c.createdBy = :user')
            ->orderBy('t.name', 'ASC')
            ->setParameter('user', $user)
            ->setParameter('scene', $scene)
        ;
    }

    public function byCurrentUserRelatedQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        $qb = $this->createTranslatableQueryBuilder('c');
        return $qb->leftJoin('c.scenes', 's')
            ->andWhere('c.createdBy = :user')
            ->andWhere($qb->expr()->andX(
                ':scene NOT MEMBER OF c.scenes',
                's.chapter = :chapter'
            ))
            ->orderBy('t.name', 'ASC')
            ->setParameter('chapter', $scene->getChapter())
            ->setParameter('scene', $scene)
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserRelatedToScenesQueryBuilder(User $user, array $scenes): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('c')
            ->join('c.scenes', 's')
            ->andWhere('c.createdBy = :user')
            ->andWhere(':scenes MEMBER OF c.scenes')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scenes', $scenes)
            ->setParameter('user', $user)
        ;
    }
}
