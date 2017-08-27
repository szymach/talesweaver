<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Repository\Traits\ValidationTrait;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class SceneRepository extends TranslatableRepository
{
    use ValidationTrait;

    public function createStandaloneQb() : QueryBuilder
    {
        return $this->createQueryBuilder('s')->where('s.chapter IS NULL');
    }

    public function createForChapterQb(Chapter $chapter) : QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.chapter = :chapter')
            ->setParameter('chapter', $chapter)
        ;
    }

    public function findLatest($limit = 5)
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.updatedAt', 'DESC')
            ->addOrderBy('s.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function firstCharacterOccurence(UuidInterface $id) : string
    {
        return $this->createFirstOccurenceQueryBuilder($id)
            ->join('s.characters', 'c')
            ->where('c MEMBER OF s.characters')
            ->andWhere('c.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function firstItemOccurence(UuidInterface $id) : string
    {
        return $this->createFirstOccurenceQueryBuilder($id)
            ->join('s.items', 'i')
            ->where('i MEMBER OF s.items')
            ->andWhere('i.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function firstLocationOccurence(UuidInterface $id) : string
    {
        return $this->createFirstOccurenceQueryBuilder($id)
            ->join('s.locations', 'l')
            ->where('l MEMBER OF s.locations')
            ->andWhere('l.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function createFirstOccurenceQueryBuilder(UuidInterface $id) : QueryBuilder
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('st.title')
            ->from($this->getEntityName(), 's')
            ->join('s.translations', 'st')
            ->setParameter('id', $id)
        ;
    }
}
