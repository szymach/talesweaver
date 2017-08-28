<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Book;
use AppBundle\Entity\Repository\Interfaces\LatestChangesAwareRepository;
use AppBundle\Entity\Repository\Traits\LatestResultsTrait;
use AppBundle\Entity\Repository\Traits\ValidationTrait;

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
