<?php

declare(strict_types=1);

namespace Talesweaver\DoctrineRepository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;

class ChapterRepository extends TranslatableRepository
{
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

    public function findForAuthor(Author $author): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.createdBy = :author')
            ->andWhere('c.book IS NULL')
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForAuthorAndBook(Author $author, Book $book): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.book = :book')
            ->andWhere('c.createdBy = :author')
            ->orderBy('c.createdAt')
            ->setParameter('book', $book)
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult()
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
