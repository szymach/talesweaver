<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

class CharacterRepository extends TranslatableRepository
{
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
        return $this->createTranslatableQueryBuilder('c')
            ->addSelect('t')
            ->andWhere(':scene MEMBER OF c.scenes')
            ->andWhere('c.createdBy = :author')
            ->orderBy('t.name', 'ASC')
            ->setParameter('author', $author)
            ->setParameter('scene', $scene)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRelatedToScene(Author $author, Scene $scene): array
    {
        $qb = $this->createTranslatableQueryBuilder('c');
        return $qb->leftJoin('c.scenes', 's')
            ->andWhere('c.createdBy = :author')
            ->andWhere($qb->expr()->andX(
                ':scene NOT MEMBER OF c.scenes',
                's.chapter = :chapter'
            ))
            ->orderBy('t.name', 'ASC')
            ->setParameter('chapter', $scene->getChapter())
            ->setParameter('scene', $scene)
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
            ->innerJoin('c.scenes', 's', Join::WITH, 's.id = :sceneId')
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
            ->innerJoin('ss.characters', 'cc', Join::WITH, 'cc.id = :id')
        ;

        return 0 !== (int) $this->countForNameQb($author, $name, $id)
            ->innerJoin('c.scenes', 's', Join::WITH, sprintf('s.id IN (%s)', $qb->getDQL()))
            ->getQuery()
            ->getSingleScalarResult()
        ;
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
