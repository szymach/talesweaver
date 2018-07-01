<?php

declare(strict_types=1);

namespace Doctrine\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Domain\Entity\Chapter;
use Domain\Entity\User;
use Ramsey\Uuid\UuidInterface;

class SceneRepository extends TranslatableRepository
{
    public function byCurrentUserStandaloneQueryBuilder(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.createdBy = :user')
            ->andWhere('s.chapter IS NULL')
            ->orderBy('s.createdAt')
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserForChapterQb(User $user, Chapter $chapter): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.chapter = :chapter')
            ->andWhere('s.createdBy = :user')
            ->orderBy('s.createdAt')
            ->setParameter('chapter', $chapter)
            ->setParameter('user', $user)
        ;
    }

    public function firstCharacterOccurence(User $user, UuidInterface $id): string
    {
        return $this->createFirstOccurenceQueryBuilder($user, $id)
            ->join('s.characters', 'c')
            ->andWhere('c MEMBER OF s.characters')
            ->andWhere('c.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function firstItemOccurence(User $user, UuidInterface $id): string
    {
        return $this->createFirstOccurenceQueryBuilder($user, $id)
            ->join('s.items', 'i')
            ->andWhere('i MEMBER OF s.items')
            ->andWhere('i.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function firstLocationOccurence(User $user, UuidInterface $id): string
    {
        return $this->createFirstOccurenceQueryBuilder($user, $id)
            ->join('s.locations', 'l')
            ->andWhere('l MEMBER OF s.locations')
            ->andWhere('l.id = :id')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function createFirstOccurenceQueryBuilder(User $user, UuidInterface $id): QueryBuilder
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('st.title')
            ->from($this->getEntityName(), 's')
            ->join('s.translations', 'st')
            ->where('s.createdBy = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->setMaxResults(1)
        ;
    }

    public function findLatest(
        User $user,
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
            ->where('e.createdBy = :user')
            ->orderBy('date', 'DESC')
            ->setParameter('locale', $locale)
            ->setParameter('user', $user)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function entityExists(User $user, array $parameters, ?UuidInterface $id): bool
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(e.id)')
            ->from($this->getEntityName(), 'e')
            ->join('e.translations', 't')
            ->where('e.createdBy = :user')
            ->setParameter('user', $user)
        ;

        if (null !== $id) {
            $qb->andWhere('e.id != :id')->setParameter('id', $id);
        }

        foreach ($parameters as $name => $value) {
            $metadata = $this->getEntityManager()->getClassMetadata($this->getEntityName());
            list(, $fieldLabel) = explode('.', $name);
            if (is_null($value)) {
                $qb->andWhere(sprintf('%s IS NULL', $name));
            } elseif ($metadata->isCollectionValuedAssociation($fieldLabel)) {
                $joinAlias = sprintf('jAlias%s', ++$this->joinAliasCount);
                $qb->leftJoin($name, $joinAlias)->andWhere(sprintf('%s MEMBER OF %s', $joinAlias, $name));
                $condition = is_iterable($value)
                    ? sprintf('%s IN (:%s)', $joinAlias, implode(',', (array) $fieldLabel))
                    : sprintf('%s = :%s', $joinAlias, $fieldLabel)
                ;

                $qb->andWhere($condition)->setParameter($fieldLabel, $value);
            } else {
                $qb->andWhere(sprintf('%s = :%s', $name, $fieldLabel))->setParameter($fieldLabel, $value);
            }
        }

        return (int) $qb->getQuery()->getSingleScalarResult() !== 0;
    }
}
