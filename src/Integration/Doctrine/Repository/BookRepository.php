<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Query\Expr\Join;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Publication;
use Talesweaver\Domain\ValueObject\Sort;

final class BookRepository extends AutoWireableTranslatableRepository
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
            ->setParameter('author', $author)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function createListView(Author $author, ?Sort $sort): array
    {
        $query = $this->getEntityManager()
            ->getConnection()
            ->createQueryBuilder()
            ->select('b.id, bt.title')
            ->from($this->getClassMetadata()->getTableName(), 'b')
            ->innerJoin('b', 'book_translation', 'bt', 'b.id = bt.book_id AND bt.locale = :locale')
            ->where('b.created_by_id = :author')
            ->setParameter('author', $author->getId())
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
        ;

        if (null !== $sort) {
            switch ($sort->getField()) {
                case 'title':
                    $query->orderBy('bt.title', $sort->getDirection());
                    break;
                default:
                    $query->orderBy('bt.title', 'asc');
            }
        }

        $statement = $query->execute();
        if (false === $statement instanceof Statement) {
            return [];
        }

        return $statement->fetchAll(FetchMode::ASSOCIATIVE);
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

    public function createPublicationListPage(Author $author, Book $book): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p.id, p.title, p.createdAt, p.locale, p.visible')
            ->from(Publication::class, 'p')
            ->innerJoin(Book::class, 'b', Join::WITH, 'p MEMBER OF b.publications AND b = :book')
            ->where('b.createdBy = :author')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('author', $author)
            ->setParameter('book', $book)
            ->getQuery()
            ->getResult()
        ;
    }
}
