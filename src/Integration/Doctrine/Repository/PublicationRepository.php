<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Publication;

final class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    public function remove(Author $author, UuidInterface $id): void
    {
        $this->createQueryBuilder('p')
            ->delete()
            ->where('p.id = :id')
            ->andWhere('p.createdBy = :createdBy')
            ->getQuery()
            ->execute(['id' => $id->toString(), 'createdBy' => $author])
        ;
    }
}
