<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Talesweaver\Integration\Doctrine\Entity\PasswordResetToken;

class PasswordResetTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordResetToken::class);
    }

    public function findOneByEmail(string $email): ?PasswordResetToken
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findOneByCode(string $code): ?PasswordResetToken
    {
        return $this->findOneBy(['value' => $code]);
    }

    public function findCreationDateOfPrevious(string $email): ?DateTimeImmutable
    {
        $date = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('pt.createdAt AS creationDate')
            ->from($this->getEntityName(), 'pt')
            ->join('pt.user', 'u')
            ->join('u.author', 'a', Join::WITH, 'a.username = :email')
            ->where('pt.active = true')
            ->orderBy('pt.createdAt', 'DESC')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $date['creationDate'] ?? null;
    }

    public function deactivatePreviousTokens(string $email): void
    {
        $previousTokensIds = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('pt.id')
            ->from($this->getEntityName(), 'pt')
            ->join('pt.user', 'u')
            ->join('u.author', 'a', Join::WITH, 'a.username = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult()
        ;

        if (0 === count($previousTokensIds)) {
            return;
        }

        $this->getEntityManager()
            ->createQueryBuilder()
            ->update($this->getEntityName(), 'pt')
            ->set('pt.active', ':false')
            ->where('pt.id IN (:ids)')
            ->setParameter('ids', $previousTokensIds)
            ->setParameter('false', false)
            ->getQuery()
            ->execute()
        ;
    }
}
