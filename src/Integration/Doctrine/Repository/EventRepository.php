<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

final class EventRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

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
        return $this->createForAuthorQueryBuilder($author)
            ->andWhere('e.scene = :scene')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scene', $scene)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForCharacter(Author $author, Character $character): array
    {
        return $this->createForSceneItemAndAuthorQueryBuilder($author)
            ->andWhere(':character MEMBER OF e.characters')
            ->setParameter('character', $character)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForItem(Author $author, Item $item): array
    {
        return $this->createForSceneItemAndAuthorQueryBuilder($author)
            ->andWhere(':item MEMBER OF e.items')
            ->setParameter('item', $item)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForLocation(Author $author, Location $location): array
    {
        return $this->createForSceneItemAndAuthorQueryBuilder($author)
            ->andWhere('e.location = :location')
            ->setParameter('location', $location)
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
            ->createQueryBuilder()
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

    private function createForSceneItemAndAuthorQueryBuilder(Author $author): QueryBuilder
    {
        return $this->createForAuthorQueryBuilder($author)
            ->innerJoin('e.scene', 's')
            ->orderBy('s.position', 'asc')
            ->addOrderBy('t.name', 'ASC')
        ;
    }

    private function createForAuthorQueryBuilder(Author $author): QueryBuilder
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('e.id, t.name')
            ->from($this->getEntityName(), 'e')
            ->innerJoin('e.translations', 't', Join::WITH, 't.locale = :locale')
            ->where('e.createdBy = :author')
            ->groupBy('e.id')
            ->setParameter('author', $author)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
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
