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

    public function findLatestStandalone()
    {
        return $this->createStandaloneQb()
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
}
