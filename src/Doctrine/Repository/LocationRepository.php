<?php

declare(strict_types=1);

namespace Doctrine\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Domain\Scene;
use Domain\User;
use Ramsey\Uuid\UuidInterface;

class LocationRepository extends TranslatableRepository
{
    /**
     * @var int
     */
    private $joinAliasCount = 0;

    public function byCurrentUserForSceneQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('l')
            ->andWhere(':scene MEMBER OF l.scenes')
            ->andWhere('l.createdBy = :user')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scene', $scene)
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserRelatedQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        $qb = $this->createTranslatableQueryBuilder('l');
        return $qb->leftJoin('l.scenes', 's')
            ->where('l.createdBy = :user')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF l.scenes',
                    's.chapter = :chapter'
                )
            )
            ->andWhere(':scene NOT MEMBER OF l.scenes')
            ->orWhere('s.id IS NULL')
            ->orderBy('t.name', 'ASC')
            ->setParameter('chapter', $scene->getChapter())
            ->setParameter('scene', $scene)
            ->setParameter('user', $user)
        ;
    }

    public function byCurrentUserRelatedToScenesQueryBuilder(User $user, array $scenes): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('l')
            ->join('l.scenes', 's')
            ->where('l.createdBy = :user')
            ->andWhere(':scenes MEMBER OF l.scenes')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scenes', $scenes)
            ->setParameter('user', $user)
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
