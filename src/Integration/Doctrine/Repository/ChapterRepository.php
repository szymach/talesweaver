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
use Talesweaver\Domain\Publication;
use Talesweaver\Domain\ValueObject\Sort;

final class ChapterRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function persist(Chapter $chapter): void
    {
        $this->getEntityManager()->persist($chapter);

        if (null !== $chapter->getBook()) {
            $this->getEntityManager()
                ->createQueryBuilder()
                ->update($this->getEntityName(), 'c')
                ->set('c.position', 'c.position + 1')
                ->where('c.book = :book')
                ->andWhere('c.createdBy = :createdBy')
                ->getQuery()
                ->execute(['book' => $chapter->getBook(), 'createdBy' => $chapter->getCreatedBy()])
            ;
        }
    }

    public function findByIdAndAuthor(UuidInterface $id, Author $author): ?Chapter
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.translations', 't', Join::WITH, 't.locale = :locale')
            ->where('c.id = :id')
            ->andWhere('c.createdBy = :createdBy')
            ->setParameter('id', $id)
            ->setParameter('createdBy', $author->getId())
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function remove(Author $author, Chapter $chapter): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->delete($this->getEntityName(), 'c')
            ->where('c.id = :id')
            ->andWhere('c.createdBy = :createdBy')
            ->getQuery()
            ->execute(['id' => $chapter->getId(), 'createdBy' => $author])
        ;

        if (null !== $chapter->getBook()) {
            $this->getEntityManager()
                ->createQueryBuilder()
                ->update($this->getEntityName(), 'c')
                ->set('c.position', 'c.position - 1')
                ->where('c.book = :book')
                ->andWhere('c.position > :position')
                ->andWhere('c.position > 0')
                ->andWhere('c.createdBy = :createdBy')
                ->getQuery()
                ->execute([
                    'book' => $chapter->getBook(),
                    'position' => $chapter->getPosition(),
                    'createdBy' => $author
                ])
            ;
        }
    }

    public function createListView(Author $author, ?Book $book, ?Sort $sort): array
    {
        $query = $this->getEntityManager()
            ->getConnection()
            ->createQueryBuilder()
            ->select('c.id, c.position, ct.title AS title')
            ->addSelect('bt.title AS book')
            ->from($this->getClassMetadata()->getTableName(), 'c')
            ->innerJoin('c', 'chapter_translation', 'ct', 'c.id = ct.chapter_id AND ct.locale = :locale')
            ->leftJoin('c', 'book', 'b', 'c.book_id = b.id')
            ->leftJoin('b', 'book_translation', 'bt', 'b.id = bt.book_id AND bt.locale = :locale')
            ->where('c.created_by_id = :author')
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
        } elseif (null !== $book) {
            $query->orderBy('c.position');
        } else {
            $query->orderBy('bt.title')->addOrderBy('ct.title');
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

    public function createPublicationListPage(Author $author, Chapter $chapter): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p.id, p.title, p.createdAt, p.locale, p.visible')
            ->from(Publication::class, 'p')
            ->innerJoin(Chapter::class, 'c', Join::WITH, 'p MEMBER OF c.publications AND c = :chapter')
            ->where('c.createdBy = :author')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('author', $author)
            ->setParameter('chapter', $chapter)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countForBook(Book $book): int
    {
        return (int) $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(c)')
            ->from($this->getEntityName(), 'c')
            ->where('c.book = :book')
            ->setParameter('book', $book)
            ->getQuery()
            ->getSingleScalarResult()
        ;
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
