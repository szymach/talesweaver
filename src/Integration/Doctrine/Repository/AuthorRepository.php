<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\ValueObject\Email;

class AuthorRepository extends EntityRepository
{
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
