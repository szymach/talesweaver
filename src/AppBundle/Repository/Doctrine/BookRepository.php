<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\User;
use AppBundle\Repository\Traits\LatestResultsTrait;
use AppBundle\Repository\Traits\ValidationTrait;
use Doctrine\ORM\QueryBuilder;

class BookRepository extends TranslatableRepository
{
    use LatestResultsTrait, ValidationTrait;

    public function createByUserQueryBuilder(User $user) : QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->where('b.createdBy = :user')
            ->setParameter('user', $user)
        ;
    }
}
