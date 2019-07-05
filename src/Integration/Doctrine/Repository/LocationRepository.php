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
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

final class LocationRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function persist(Location $location): void
    {
        $this->getEntityManager()->persist($location);
    }

    public function remove(Author $author, UuidInterface $id): void
    {
        $this->createQueryBuilder('l')
            ->delete()
            ->where('l.id = :id')
            ->andWhere('l.createdBy = :createdBy')
            ->getQuery()
            ->execute(['id' => $id->toString(), 'createdBy' => $author])
        ;
    }

    public function findForAuthorAndScene(Author $author, Scene $scene): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('l')
            ->addSelect('t')
            ->from($this->getEntityName(), 'l')
            ->join('l.translations', 't', Join::WITH, 't.locale = :locale')
            ->join('l.scenes', 's')
            ->where(':scene MEMBER OF l.scenes')
            ->andWhere('l.createdBy = :author')
            ->orderBy('t.name', 'ASC')
            ->groupBy('l.id')
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
        $qb = $this->createQueryBuilder('l')
            ->innerJoin('l.translations', 't', Join::WITH, 't.locale = :locale')
            ->innerJoin('l.scenes', 's')
            ->andWhere('l.createdBy = :author')
        ;

        if (null !== $scene->getChapter()->getBook()) {
            $qb->innerJoin('s.chapter', 'ch')
                ->innerJoin('ch.book', 'b')
                ->andWhere(
                    $qb->expr()->andX(
                        ':scene NOT MEMBER OF l.scenes',
                        'ch.book = :book'
                    )
                )->setParameter('book', $scene->getChapter()->getBook());
        } else {
            $qb->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF l.scenes',
                    's.chapter = :chapter'
                )
            )->setParameter('chapter', $scene->getChapter());
        }

        return $qb->orderBy('t.name', 'ASC')
            ->groupBy('l.id')
            ->setParameter('scene', $scene)
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
            $qb->innerJoin('l.scenes', 's', Join::WITH, 's = :scene')->setParameter('scene', $scene);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function nameConflictsWithRelated(Author $author, string $name, UuidInterface $id): bool
    {
        $bookLocationsDQL = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT(bs.id)')
            ->from(Book::class, 'b')
            ->innerJoin('b.chapters', 'bch')
            ->innerJoin('bch.scenes', 'bs')
            ->innerJoin('bs.locations', 'bc', Join::WITH, 'bc.id = :id')
            ->getDQL()
        ;

        $chapterLocationsDQL = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT(cs.id)')
            ->from(Chapter::class, 'ch')
            ->innerJoin('ch.scenes', 'cs')
            ->innerJoin('cs.locations', 'csc', Join::WITH, 'csc.id = :id')
            ->getDQL()
        ;

        $qb = $this->countForNameQb($author, $name, $id);
        return 0 !== (int) $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->in('l.id', $bookLocationsDQL),
                $qb->expr()->in('l.id', $chapterLocationsDQL)
            )
        )->getQuery()->getSingleScalarResult();
    }

    private function countForNameQb(Author $author, string $name, ?UuidInterface $id): QueryBuilder
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(l.id)')
            ->from($this->getEntityName(), 'l')
            ->innerJoin('l.translations', 't', Join::WITH, 't.locale = :locale AND t.name = :name')
            ->where('l.createdBy = :author')
            ->setParameter('name', $name)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->setParameter('author', $author)
        ;

        if (null !== $id) {
            $qb->andWhere('l.id != :id')->setParameter('id', $id);
        }

        return $qb;
    }
}
