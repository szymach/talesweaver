<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\User;
use AppBundle\Repository\Traits\LatestResultsTrait;
use AppBundle\Repository\Traits\ValidationTrait;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class SceneRepository extends TranslatableRepository
{
    use LatestResultsTrait, ValidationTrait;

    public function byCurrentUserStandaloneQueryBuilder(User $user) : QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.createdBy = :user')
            ->andWhere('s.chapter IS NULL')
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserForChapterQb(User $user, Chapter $chapter) : QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.chapter = :chapter')
            ->andWhere('s.createdBy = :user')
            ->setParameter('chapter', $chapter)
            ->setParameter('user', $user)
        ;
    }

    public function firstCharacterOccurence(User $user, UuidInterface $id) : string
    {
        return $this->createFirstOccurenceQueryBuilder($user, $id)
            ->join('s.characters', 'c')
            ->where('c MEMBER OF s.characters')
            ->andWhere('c.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function firstItemOccurence(User $user, UuidInterface $id) : string
    {
        return $this->createFirstOccurenceQueryBuilder($user, $id)
            ->join('s.items', 'i')
            ->where('i MEMBER OF s.items')
            ->andWhere('i.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function firstLocationOccurence(User $user, UuidInterface $id) : string
    {
        return $this->createFirstOccurenceQueryBuilder($user, $id)
            ->join('s.locations', 'l')
            ->where('l MEMBER OF s.locations')
            ->andWhere('l.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function createFirstOccurenceQueryBuilder(User $user, UuidInterface $id) : QueryBuilder
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('st.title')
            ->from($this->getEntityName(), 's')
            ->join('s.translations', 'st')
            ->where('s.createdBy = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
        ;
    }
}