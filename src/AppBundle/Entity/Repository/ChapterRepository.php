<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Book;
use AppBundle\Entity\Repository\Traits\ValidationTrait;

class ChapterRepository extends TranslatableRepository
{
    use ValidationTrait;

    public function createStandaloneQb()
    {
        return $this->createQueryBuilder('c')->where('c.book IS NULL');
    }

    public function createForBookQb(Book $book)
    {
        return $this->createQueryBuilder('c')
            ->where('c.book = :book')
            ->setParameter('book', $book)
        ;
    }

    public function findLatest($limit = 5)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.updatedAt', 'DESC')
            ->addOrderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

}
