<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Talesweaver\Integration\Doctrine\Entity\User;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

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

    public function findOneByUsername(string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->join('u.author', 'a', Join::WITH, 'a.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
