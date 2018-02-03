<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use Domain\Entity\User;
use App\Repository\Traits\LatestResultsTrait;
use App\Repository\Traits\ValidationTrait;
use Doctrine\ORM\QueryBuilder;

class BookRepository extends TranslatableRepository
{
    use LatestResultsTrait, ValidationTrait;

    public function createByUserQueryBuilder(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->where('b.createdBy = :user')
            ->setParameter('user', $user)
        ;
    }
}
