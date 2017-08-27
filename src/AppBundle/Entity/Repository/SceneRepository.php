<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Character;
use AppBundle\Entity\Repository\Traits\ValidationTrait;
use Doctrine\ORM\QueryBuilder;

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

    public function firstCharacterOccurence(Character $character) : string
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('st.title')
            ->from($this->getEntityName(), 's')
            ->join('s.translations', 'st')
            ->where(':character MEMBER OF s.characters')
            ->setParameter('character', $character)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
