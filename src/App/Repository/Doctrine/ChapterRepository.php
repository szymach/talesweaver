<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Repository\Traits\LatestResultsTrait;
use App\Repository\Traits\ValidationTrait;
use Doctrine\ORM\QueryBuilder;
use Domain\Entity\Book;
use Domain\Entity\User;

class ChapterRepository extends TranslatableRepository
{
    use LatestResultsTrait, ValidationTrait;

    public function allAvailableByUserQueryBuilder(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.createdBy = :user')
            ->orderBy('c.book')
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserQueryBuilder(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.createdBy = :user')
            ->andWhere('c.book IS NULL')
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserForBookQueryBuilder(User $user, Book $book): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.book = :book')
            ->andWhere('c.createdBy = :user')
            ->orderBy('c.createdAt')
            ->setParameter('book', $book)
            ->setParameter('user', $user)
        ;
    }
}
