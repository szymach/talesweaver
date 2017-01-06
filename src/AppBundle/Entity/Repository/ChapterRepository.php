<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Book;

class ChapterRepository extends TranslatableRepository
{
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
            ->orderBy('c.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
