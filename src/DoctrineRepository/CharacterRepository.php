<?php

declare(strict_types=1);

namespace Talesweaver\DoctrineRepository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

class CharacterRepository extends TranslatableRepository
{
    /**
     * @var int
     */
    private $joinAliasCount = 0;

    public function persist(Character $character): void
    {
        $this->getEntityManager()->persist($character);
    }

    public function byCurrentAuthorForSceneQueryBuilder(Author $author, Scene $scene): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('c')
            ->addSelect('t')
            ->andWhere(':scene MEMBER OF c.scenes')
            ->andWhere('c.createdBy = :author')
            ->orderBy('t.name', 'ASC')
            ->setParameter('author', $author)
            ->setParameter('scene', $scene)
        ;
    }

    public function byCurrentAuthorRelatedQueryBuilder(Author $author, Scene $scene): QueryBuilder
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
        ;
    }

    public function byCurrentAuthorRelatedToScenesQueryBuilder(Author $author, array $scenes): QueryBuilder
    {
        return $this->createTranslatableQueryBuilder('c')
            ->join('c.scenes', 's')
            ->andWhere('c.createdBy = :author')
            ->andWhere(':scenes MEMBER OF c.scenes')
            ->orderBy('t.name', 'ASC')
            ->setParameter('scenes', $scenes)
            ->setParameter('author', $author)
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

    public function entityExists(Author $author, array $parameters, ?UuidInterface $id): bool
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(e.id)')
            ->from($this->getEntityName(), 'e')
            ->join('e.translations', 't')
            ->where('e.createdBy = :author')
            ->setParameter('author', $author)
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

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }
}
