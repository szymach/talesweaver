<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Scene;

class SceneRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scene::class);
    }

    public function persist(Scene $scene): void
    {
        $this->getEntityManager()->persist($scene);
    }

    public function remove(Author $author, UuidInterface $id): void
    {
        $this->createQueryBuilder('s')
            ->delete()
            ->where('s.id = :id')
            ->andWhere('s.createdBy = :createdBy')
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

    public function existsStandaloneWithTitle(Author $author, string $title, ?UuidInterface $id): bool
    {
        $qb = $this->countForTitleQb($author, $title)->andWhere('s.chapter IS NULL');

        if (null !== $id) {
            $qb->andWhere('s.id != :id')->setParameter('id', $id);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function existsAssignedWithTitle(
        Author $author,
        string $title,
        UuidInterface $chapterId,
        ?UuidInterface $id
    ): bool {
        $qb = $this->countForTitleQb($author, $title)
            ->innerJoin('s.chapter', 'c', Join::WITH, 'c.id = :chapterId')
            ->setParameter('chapterId', $chapterId)
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
            ->select('COUNT(s.id)')
            ->from($this->getEntityName(), 's')
            ->join('s.translations', 't', Join::WITH, 't.locale = :locale AND t.title = :title')
            ->where('s.createdBy = :author')
            ->setParameter('author', $author)
            ->setParameter('title', $title)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
        ;
    }
}
