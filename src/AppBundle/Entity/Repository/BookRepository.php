<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Repository\Traits\ValidationTrait;

class BookRepository extends TranslatableRepository
{
    use ValidationTrait;

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
