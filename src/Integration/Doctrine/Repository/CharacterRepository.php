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
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

final class CharacterRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    public function persist(Character $character): void
    {
        $this->getEntityManager()->persist($character);
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

    public function findForAuthorAndScene(Author $author, Scene $scene): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('c')
            ->addSelect('t')
            ->from($this->getEntityName(), 'c')
            ->join('c.translations', 't', Join::WITH, 't.locale = :locale')
            ->join('c.scenes', 's')
            ->where(':scene MEMBER OF c.scenes')
            ->andWhere('c.createdBy = :author')
            ->orderBy('t.name', 'ASC')
            ->groupBy('c.id')
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
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.translations', 't', Join::WITH, 't.locale = :locale')
            ->innerJoin('c.scenes', 's')
            ->andWhere('c.createdBy = :author')
        ;

        if (null !== $scene->getChapter()->getBook()) {
            $qb->innerJoin('s.chapter', 'ch')
                ->innerJoin('ch.book', 'b')
                ->andWhere(
                    $qb->expr()->andX(
                        ':scene NOT MEMBER OF c.scenes',
                        'ch.book = :book'
                    )
                )->setParameter('book', $scene->getChapter()->getBook());
        } else {
            $qb->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF c.scenes',
                    's.chapter = :chapter'
                )
            )->setParameter('chapter', $scene->getChapter());
        }

        return $qb->orderBy('t.name', 'ASC')
            ->groupBy('c.id')
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
            if (null !== $scene->getChapter()->getBook()) {
                $qb->innerJoin(Book::class, 'b', Join::WITH, 'b = :book')
                    ->setParameter('book', $scene->getChapter()->getBook())
                ;
            } else {
                $qb->innerJoin(Chapter::class, 'ch', Join::WITH, 'ch = :chapter')
                    ->setParameter('chapter', $scene->getChapter())
                ;
            }
        } else {
            $qb->innerJoin('c.scenes', 's', Join::WITH, 's = :scene')->setParameter('scene', $scene);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function nameConflictsWithRelated(Author $author, string $name, UuidInterface $id): bool
    {
        $bookCharactersDQL = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT(bs.id)')
            ->from(Book::class, 'b')
            ->innerJoin('b.chapters', 'bch')
            ->innerJoin('bch.scenes', 'bs')
            ->innerJoin('bs.characters', 'bc', Join::WITH, 'bc.id = :id')
            ->getDQL()
        ;

        $chapterCharactersDQL = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT(cs.id)')
            ->from(Chapter::class, 'ch')
            ->innerJoin('ch.scenes', 'cs')
            ->innerJoin('cs.characters', 'csc', Join::WITH, 'csc.id = :id')
            ->getDQL()
        ;

        $qb = $this->countForNameQb($author, $name, $id);
        return 0 !== (int) $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->in('c.id', $bookCharactersDQL),
                $qb->expr()->in('c.id', $chapterCharactersDQL)
            )
        )->getQuery()->getSingleScalarResult();
    }

    private function countForNameQb(Author $author, string $name, ?UuidInterface $id): QueryBuilder
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from($this->getEntityName(), 'c')
            ->innerJoin('c.translations', 't', Join::WITH, 't.locale = :locale AND t.name = :name')
            ->where('c.createdBy = :author')
            ->setParameter('name', $name)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->setParameter('author', $author)
        ;

        if (null !== $id) {
            $qb->andWhere('c.id != :id')->setParameter('id', $id);
        }

        return $qb;
    }
}
