<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\User;
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
