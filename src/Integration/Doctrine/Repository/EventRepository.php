<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Scene;

class EventRepository extends TranslatableRepository
{
    public function persist(Event $event): void
    {
        $this->getEntityManager()->persist($event);
    }

    public function remove(Author $author, UuidInterface $id): void
    {
        $this->createQueryBuilder('e')
            ->delete()
            ->where('e.id = :id')
            ->andWhere('e.createdBy = :createdBy')
            ->getQuery()
            ->execute(['id' => $id->toString(), 'createdBy' => $author])
        ;
    }

    public function findForScene(Author $author, Scene $scene): array
    {
        return $this->createTranslatableQueryBuilder('e')
            ->where('e.scene = :scene')
            ->andWhere('e.createdBy = :author')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scene', $scene)
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findInEventsById(Author $author, UuidInterface $id): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.model LIKE :id')
            ->andWhere('e.createdBy = :author')
            ->setParameter('id', sprintf('%%"%s"%%', $id))
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLatest(
        Author $author,
        string $locale,
        string $label = 'title',
        int $limit = 5
    ): array {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('(CASE WHEN e.updatedAt IS NOT NULL THEN e.updatedAt ELSE e.createdAt END) AS date')
            ->addSelect('(CASE WHEN e.updatedAt IS NOT NULL THEN 1 ELSE 0 END) AS updated')
            ->addSelect('e.id')
            ->addSelect(sprintf('t.%s AS label', $label))
            ->from($this->getEntityName(), 'e')
            ->join('e.translations', 't', Join::WITH, 't.locale = :locale')
            ->where('e.createdBy = :author')
            ->orderBy('date', 'DESC')
            ->setParameter('locale', $locale)
            ->setParameter('author', $author)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function existsForSceneWithName(Author $author, string $name, UuidInterface $sceneId): bool
    {
        return 0 !== (int) $this->countForNameQb($author, $name, null)
            ->innerJoin('e.scene', 's', Join::WITH, 's.id = :sceneId')
            ->setParameter('sceneId', $sceneId)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function nameConflictsWithRelated(Author $author, string $name, UuidInterface $id): bool
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder('ss')
            ->select('ss.id')
            ->from(Scene::class, 'ss')
            ->innerJoin('ss.events', 'ee', Join::WITH, 'ee.id = :id')
        ;

        return 0 !== (int) $this->countForNameQb($author, $name, $id)
            ->innerJoin('e.scene', 's', Join::WITH, sprintf('s.id IN (%s)', $qb->getDQL()))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function countForNameQb(Author $author, string $name, ?UuidInterface $id): QueryBuilder
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(e.id)')
            ->from($this->getEntityName(), 'e')
            ->innerJoin('e.translations', 't', Join::WITH, 't.locale = :locale AND t.name = :name')
            ->where('e.createdBy = :author')
            ->setParameter('name', $name)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->setParameter('author', $author)
        ;

        if (null !== $id) {
            $qb->andWhere('e.id != :id')->setParameter('id', $id);
        }

        return $qb;
    }
}
