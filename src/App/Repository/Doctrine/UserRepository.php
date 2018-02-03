<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use Domain\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

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
