<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use Domain\Entity\Book;
use Domain\Entity\User;
use App\Repository\Traits\LatestResultsTrait;
use App\Repository\Traits\ValidationTrait;
use Doctrine\ORM\QueryBuilder;

class ChapterRepository extends TranslatableRepository
{
    use LatestResultsTrait, ValidationTrait;

    public function byCurrentUserQueryBuilder(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.createdBy = :user')
            ->andWhere('c.book IS NULL')
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserForBookQueryBuilder(User $user, Book $book)
    {
        return $this->createQueryBuilder('c')
            ->where('c.book = :book')
            ->andWhere('c.createdBy = :user')
            ->setParameter('book', $book)
            ->setParameter('user', $user)
        ;
    }
}
