<?php

declare(strict_types=1);

namespace Talesweaver\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Doctrine\Entity\User;

class ItemRepository extends TranslatableServiceRepository
{
    /**
     * @var int
     */
    private $joinAliasCount = 0;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function byCurrentUserForSceneQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('i')
            ->where(':scene MEMBER OF i.scenes')
            ->andWhere('i.createdBy = :author')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scene', $scene)
            ->setParameter('author', $user->getAuthor())
        ;
    }

    public function byCurrentUserRelatedQueryBuilder(User $user, Scene $scene): QueryBuilder
    {
        $qb = $this->createTranslatableQueryBuilder('i');
        return $qb->leftJoin('i.scenes', 's')
            ->where('i.createdBy = :author')
            ->andWhere(
                $qb->expr()->andX(
                    ':scene NOT MEMBER OF i.scenes',
                    's.chapter = :chapter'
                )
            )
            ->andWhere(':scene NOT MEMBER OF i.scenes')
            ->orderBy('t.name', 'ASC')
            ->setParameter('chapter', $scene->getChapter())
            ->setParameter('scene', $scene)
            ->setParameter('author', $user->getAuthor())
        ;
    }

    public function byCurrentUserRelatedToScenesQueryBuilder(User $user, array $scenes): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('i')
            ->join('i.scenes', 's')
            ->where('i.createdBy = :author')
            ->andWhere(':scenes MEMBER OF i.scenes')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scenes', $scenes)
            ->setParameter('author', $user->getAuthor())
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
            ->where('e.createdBy = :author')
            ->orderBy('date', 'DESC')
            ->setParameter('locale', $locale)
            ->setParameter('author', $user->getAuthor())
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
            ->where('e.createdBy = :author')
            ->setParameter('author', $user->getAuthor())
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
