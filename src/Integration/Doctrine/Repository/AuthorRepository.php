<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Authors;
use Talesweaver\Domain\ValueObject\Email;

class AuthorRepository extends ServiceEntityRepository implements Authors
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function add(Author $author): void
    {
        $this->getEntityManager()->persist($author);
    }

    public function findOneByActivationToken(string $code): ?Author
    {
        return $this->createQueryBuilder('a')
            ->join('a.activationTokens', 'ac', Join::WITH, 'ac.value = :value')
            ->groupBy('a.id')
            ->setParameter('value', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByEmail(Email $email): ?Author
    {
        return $this->createQueryBuilder('a')
            ->where('a.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
