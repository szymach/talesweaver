<?php

declare(strict_types=1);

namespace Talesweaver\DoctrineRepository;

use Doctrine\ORM\Query\Expr\Join;
use FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;

class BookRepository extends TranslatableRepository
{
    /**
     * @var int
     */
    private $joinAliasCount = 0;

    public function persist(Book $book): void
    {
        $this->getEntityManager()->persist($book);
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
