<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Assert\Assertion;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;

final class ItemRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function persist(Item $item): void
    {
        $this->getEntityManager()->persist($item);
    }

    public function remove(Author $author, UuidInterface $id): void
    {
        $this->createQueryBuilder('i')
            ->delete()
            ->where('i.id = :id')
            ->andWhere('i.createdBy = :createdBy')
            ->getQuery()
            ->execute(['id' => $id->toString(), 'createdBy' => $author])
        ;
    }

    public function findForAuthorAndScene(Author $author, Scene $scene): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->addSelect('t')
            ->from($this->getEntityName(), 'i')
            ->join('i.translations', 't', Join::WITH, 't.locale = :locale')
            ->join('i.scenes', 's')
            ->where(':scene MEMBER OF i.scenes')
            ->andWhere('i.createdBy = :author')
            ->orderBy('t.name', 'ASC')
            ->groupBy('i.id')
            ->setParameter('author', $author)
            ->setParameter('scene', $scene)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRelatedToScene(Author $author, Scene $scene): array
    {
        Assertion::notNull($scene->getChapter());
        $qb = $this->createQueryBuilder('i')
            ->innerJoin('i.translations', 't', Join::WITH, 't.locale = :locale')
            ->innerJoin('i.scenes', 's')
            ->andWhere('i.createdBy = :author')
        ;

        if (null !== $scene->getChapter()->getBook()) {
            $qb->innerJoin('s.chapter', 'ch')
                ->innerJoin('ch.book', 'b')
                ->andWhere(
                    $qb->expr()->andX(
                        ':scene NOT MEMBER OF i.scenes',
                        'ch.book = :book'
                    )
                )->setParameter('book', $scene->getChapter()->getBook());
        } else {
            $qb->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF i.scenes',
                    's.chapter = :chapter'
                )
            )->setParameter('chapter', $scene->getChapter());
        }

        return $qb->orderBy('t.name', 'ASC')
            ->groupBy('i.id')
            ->setParameter('scene', $scene)
            ->setParameter('author', $author)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForEvent(Author $author, Scene $scene): array
    {
        $qb = $this->createQueryBuilder('i')
            ->innerJoin('i.translations', 't', Join::WITH, 't.locale = :locale')
            ->innerJoin('i.scenes', 's')
            ->andWhere('i.createdBy = :author')
        ;

        $chapter = $scene->getChapter();
        if (null !== $chapter && null !== $chapter->getBook()) {
            $qb->innerJoin('s.chapter', 'ch')
                ->innerJoin('ch.book', 'b')
                ->andWhere('ch.book = :book')
                ->setParameter('book', $chapter->getBook())
            ;
        } elseif (null !== $chapter) {
            $qb->andWhere('s.chapter = :chapter')->setParameter('chapter', $chapter);
        } else {
            $qb->andWhere(':scene MEMBER OF i.scenes')->setParameter('scene', $scene);
        }

        return $qb->orderBy('t.name', 'ASC')
            ->groupBy('i.id')
            ->setParameter('author', $author)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->getQuery()
            ->getResult()
        ;
    }

    public function existsForSceneWithName(Author $author, string $name, Scene $scene): bool
    {
        $qb = $this->countForNameQb($author, $name, null);
        if (null !== $scene->getChapter()) {
            $qb->innerJoin(Chapter::class, 'ch', Join::WITH, 'ch = :chapter')
                ->setParameter('chapter', $scene->getChapter())
            ;
        } else {
            $qb->innerJoin('i.scenes', 's', Join::WITH, 's = :scene')->setParameter('scene', $scene);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function nameConflictsWithRelated(Author $author, string $name, UuidInterface $id): bool
    {
        $bookItemsDQL = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT(bs.id)')
            ->from(Book::class, 'b')
            ->innerJoin('b.chapters', 'bch')
            ->innerJoin('bch.scenes', 'bs')
            ->innerJoin('bs.items', 'bc', Join::WITH, 'bc.id = :id')
            ->getDQL()
        ;

        $chapterItemsDQL = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT(cs.id)')
            ->from(Chapter::class, 'ch')
            ->innerJoin('ch.scenes', 'cs')
            ->innerJoin('cs.items', 'csc', Join::WITH, 'csc.id = :id')
            ->getDQL()
        ;

        $qb = $this->countForNameQb($author, $name, $id);
        return 0 !== (int) $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->in('i.id', $bookItemsDQL),
                $qb->expr()->in('i.id', $chapterItemsDQL)
            )
        )->getQuery()->getSingleScalarResult();
    }

    private function countForNameQb(Author $author, string $name, ?UuidInterface $id): QueryBuilder
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(i.id)')
            ->from($this->getEntityName(), 'i')
            ->innerJoin('i.translations', 't', Join::WITH, 't.locale = :locale AND t.name = :name')
            ->where('i.createdBy = :author')
            ->setParameter('name', $name)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->setParameter('author', $author)
        ;

        if (null !== $id) {
            $qb->andWhere('i.id != :id')->setParameter('id', $id);
        }

        return $qb;
    }
}
