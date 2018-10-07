<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;

class BookRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function persist(Book $book): void
    {
        $this->getEntityManager()->persist($book);
    }

    public function remove(Author $author, UuidInterface $id): void
    {
        $this->createQueryBuilder('d')
            ->delete()
            ->where('d.id = :id')
            ->andWhere('d.createdBy = :createdBy')
            ->getQuery()
            ->execute(['id' => $id->toString(), 'createdBy' => $author])
        ;
    }

    public function findLatest(Author $author, int $limit): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('(CASE WHEN e.updatedAt IS NOT NULL THEN e.updatedAt ELSE e.createdAt END) AS date')
            ->addSelect('(CASE WHEN e.updatedAt IS NOT NULL THEN 1 ELSE 0 END) AS updated')
            ->addSelect('e.id')
            ->addSelect('t.title AS label')
            ->from($this->getEntityName(), 'e')
            ->join('e.translations', 't', Join::WITH, 't.locale = :locale')
            ->where('e.createdBy = :author')
            ->orderBy('date', 'DESC')
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->setParameter('author', $author)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function entityExists(Author $author, string $title, ?UuidInterface $id): bool
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(b.id)')
            ->from($this->getEntityName(), 'b')
            ->join('b.translations', 't', Join::WITH, 't.title = :title')
            ->where('b.createdBy = :author')
            ->setParameter('author', $author)
            ->setParameter('title', $title)
        ;

        if (null !== $id) {
            $qb->andWhere('b.id != :id')->setParameter('id', $id);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }
}
