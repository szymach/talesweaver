<?php


namespace AppBundle\Repository\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @method EntityManagerInterface getEntityManager
 * @method string getEntityName
 */
trait ValidationTrait
{
    /**
     * @var int
     */
    private $joinAliasCount = 0;

    public function entityExists(array $parameters, ?UuidInterface $id)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(e.id)')
            ->from($this->getEntityName(), 'e')
            ->join('e.translations', 't')
        ;

        if ($id) {
            $qb->where('e.id != :id')->setParameter('id', $id);
        }

        foreach ($parameters as $name => $value) {
            $metadata = $this->getEntityManager()->getClassMetadata($this->getEntityName());
            list(, $fieldLabel) = explode('.', $name);
            if (is_null($value)) {
                $qb->andWhere(sprintf('%s IS NULL', $name));
            } elseif ($metadata->isCollectionValuedAssociation($fieldLabel)) {
                $joinAlias = sprintf('jAlias%s', ++$this->joinAliasCount);
                $qb->leftJoin($name, $joinAlias)
                    ->andWhere(sprintf('%s MEMBER OF %s', $joinAlias, $name))
                    ->andWhere(sprintf('%s = :%s', $joinAlias, $fieldLabel))
                    ->setParameter($fieldLabel, $value)
                ;
            } else {
                $qb->andWhere(sprintf('%s = :%s', $name, $fieldLabel))
                    ->setParameter($fieldLabel, $value)
                ;
            }
        }

        return (int) $qb->getQuery()->getSingleScalarResult() !== 0;
    }
}
