<?php

declare(strict_types=1);

namespace Talesweaver\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Talesweaver\Domain\User;

class UserRepository extends EntityRepository
{
    public function findOneByActivationToken(string $code): ?User
    {
        return $this->createQueryBuilder('u')
            ->join('u.activationTokens', 'ac', Join::WITH, 'ac.value = :value')
            ->groupBy('u.id')
            ->setParameter('value', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
