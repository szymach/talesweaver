<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;

class PasswordResetTokenRepository extends EntityRepository
{
    public function findCreationDateOfPrevious(string $email): ?DateTimeImmutable
    {
        $date = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('pt.createdAt AS creationDate')
            ->from($this->getEntityName(), 'pt')
            ->join('pt.user', 'u')
            ->where('pt.active = true')
            ->andWhere('u.username = :email')
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
            ->where('u.username = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult()
        ;

        if (!count($previousTokensIds)) {
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
