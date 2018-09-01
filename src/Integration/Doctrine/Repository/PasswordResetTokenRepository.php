<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Domain\PasswordResetToken;

class PasswordResetTokenRepository extends EntityRepository
{
    public function findOneByEmail(string $email): ?PasswordResetToken
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findOneByCode(string $code): ?PasswordResetToken
    {
        return $this->findOneBy(['value' => $code]);
    }

    public function findCreationDateOfPrevious(Email $email): ?DateTimeImmutable
    {
        $date = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('pt.createdAt AS creationDate')
            ->from($this->getEntityName(), 'pt')
            ->join('pt.user', 'u')
            ->join('u.author', 'a', Join::WITH, 'a.email = :email')
            ->where('pt.active = true')
            ->orderBy('pt.createdAt', 'DESC')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $date['creationDate'] ?? null;
    }

    public function deactivatePreviousTokens(Email $email): void
    {
        $previousTokensIds = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('pt.id')
            ->from($this->getEntityName(), 'pt')
            ->join('pt.user', 'u')
            ->join('u.author', 'a', Join::WITH, 'a.email = :email')
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
