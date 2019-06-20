<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

class LocationRepository extends AutoWireableTranslatableRepository
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
        return $this->createTranslatableQueryBuilder('l')
            ->andWhere(':scene MEMBER OF l.scenes')
            ->andWhere('l.createdBy = :author')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scene', $scene)
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRelatedToScene(Author $author, Scene $scene): array
    {
        $qb = $this->createTranslatableQueryBuilder('l');
        return $qb->leftJoin('l.scenes', 's')
            ->where('l.createdBy = :author')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF l.scenes',
                    's.chapter = :chapter'
                )
            )
            ->andWhere(':scene NOT MEMBER OF l.scenes')
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
            ->innerJoin('l.scenes', 's', Join::WITH, 's.id = :sceneId')
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
            ->innerJoin('ss.locations', 'll', Join::WITH, 'll.id = :id')
        ;

        return 0 !== (int) $this->countForNameQb($author, $name, $id)
            ->innerJoin('l.scenes', 's', Join::WITH, sprintf('s.id IN (%s)', $qb->getDQL()))
            ->getQuery()
            ->getSingleScalarResult()
        ;
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
