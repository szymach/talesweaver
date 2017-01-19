<?php

namespace AppBundle\Entity\Repository;

class BookRepository extends TranslatableRepository
{
    public function findLatest($limit = 5)
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.updatedAt', 'DESC')
            ->addOrderBy('b.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
