<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Book;
use AppBundle\Repository\Interfaces\LatestChangesAwareRepository;
use AppBundle\Repository\Traits\LatestResultsTrait;
use AppBundle\Repository\Traits\ValidationTrait;

class ChapterRepository extends TranslatableRepository implements LatestChangesAwareRepository
{
    use LatestResultsTrait, ValidationTrait;

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
}
