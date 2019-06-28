<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\Sort;

class ChapterRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function persist(Chapter $chapter): void
    {
        $this->getEntityManager()->persist($chapter);
    }

    public function remove(Author $author, UuidInterface $id): void
    {
        $this->createQueryBuilder('c')
            ->delete()
            ->where('c.id = :id')
            ->andWhere('c.createdBy = :createdBy')
            ->getQuery()
            ->execute(['id' => $id->toString(), 'createdBy' => $author])
        ;
    }

    public function createListView(Author $author, ?Book $book, ?Sort $sort): array
    {
        $query = $this->getEntityManager()
            ->getConnection()
            ->createQueryBuilder()
            ->select('c.id, ct.title AS title')
            ->addSelect('bt.title AS book')
            ->from($this->getClassMetadata()->getTableName(), 'c')
            ->leftJoin('c', 'chapter_translation', 'ct', 'c.id = ct.chapter_id AND ct.locale = :locale')
            ->leftJoin('c', 'book', 'b', 'c.book_id = b.id')
            ->leftJoin('b', 'book_translation', 'bt', 'b.id = bt.book_id AND bt.locale = :locale')
            ->where('c.created_by_id = :author')
            ->orderBy('c.book_id')
            ->addOrderBy('ct.title')
            ->setParameter('author', $author->getId()->toString())
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
        ;

        if (null !== $sort) {
            switch ($sort->getField()) {
                case 'title':
                    $query->orderBy('title', $sort->getDirection());
                    break;
                case 'book':
                    $query->orderBy('book', $sort->getDirection());
                    break;
                default:
                    $query->orderBy('title', 'asc');
            }
        }

        if (null !== $book) {
            $query->andWhere('b.id = :book')->setParameter('book', $book->getId()->toString());
        }

        $statement = $query->execute();
        if (false === $statement instanceof Statement) {
            return [];
        }

        return $statement->fetchAll(FetchMode::ASSOCIATIVE);
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

    public function existsStandaloneWithTitle(Author $author, string $title, ?UuidInterface $id): bool
    {
        $qb = $this->countForTitleQb($author, $title)->andWhere('c.book IS NULL');

        if (null !== $id) {
            $qb->andWhere('c.id != :id')->setParameter('id', $id);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function existsAssignedWithTitle(
        Author $author,
        string $title,
        UuidInterface $bookId,
        ?UuidInterface $id
    ): bool {
        $qb = $this->countForTitleQb($author, $title)
            ->innerJoin('c.book', 'b', Join::WITH, 'b.id = :bookId')
            ->setParameter('bookId', $bookId)
        ;

        if (null !== $id) {
            $qb->andWhere('c.id != :id')->setParameter('id', $id);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function countForTitleQb(Author $author, string $title): QueryBuilder
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from($this->getEntityName(), 'c')
            ->join('c.translations', 't', Join::WITH, 't.locale = :locale AND t.title = :title')
            ->where('c.createdBy = :author')
            ->setParameter('author', $author)
            ->setParameter('title', $title)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
        ;
    }
}
