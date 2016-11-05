<?php

namespace AppBundle\Entity\Repository;

class SceneRepository extends TranslatableRepository
{
    public function createStandaloneQb()
    {
        return $this->createQueryBuilder('s')->where('s.chapter IS NULL');
    }

    public function findLatestStandalone()
    {
        return $this->createStandaloneQb()
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
}
