<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Chapter;
use Doctrine\ORM\QueryBuilder;

class SceneRepository extends TranslatableRepository
{
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

    public function findLatestStandalone($limit = 5)
    {
        return $this->createStandaloneQb()
            ->orderBy('s.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
