<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;

class ItemRepository extends AutoWireableTranslatableRepository
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
        $qb = $this->createTranslatableQueryBuilder('i');
        return $qb->leftJoin('i.scenes', 's')
            ->andWhere('i.createdBy = :author')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF i.scenes',
                    's.chapter = :chapter'
                )
            )
            ->orderBy('t.name', 'ASC')
            ->setParameter('chapter', $scene->getChapter())
            ->setParameter('scene', $scene)
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult()
        ;
    }

    public function existsForSceneWithName(Author $author, string $name, UuidInterface $sceneId): bool
    {
        return 0 !== (int) $this->countForNameQb($author, $name, null)
            ->innerJoin('i.scenes', 's', Join::WITH, 's.id = :sceneId')
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
            ->innerJoin('ss.items', 'ii', Join::WITH, 'ii.id = :id')
        ;

        return 0 !== (int) $this->countForNameQb($author, $name, $id)
            ->innerJoin('i.scenes', 's', Join::WITH, sprintf('s.id IN (%s)', $qb->getDQL()))
            ->getQuery()
            ->getSingleScalarResult()
        ;
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
