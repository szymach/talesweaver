<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method EntityManagerInterface getEntityManager
 * @method string getEntityName
 */
trait LatestResultsTrait
{
    /**
     * @param string $locale
     * @param string $label
     * @param int $limit
     * @return array
     */
    public function findLatest(string $locale, string $label = 'title', int $limit = 5) : array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('(CASE WHEN e.updatedAt IS NOT NULL THEN e.updatedAt ELSE e.createdAt END) AS date')
            ->addSelect('(CASE WHEN e.updatedAt IS NOT NULL THEN 1 ELSE 0 END) AS updated')
            ->addSelect('e.id')
            ->addSelect(sprintf('t.%s AS label', $label))
            ->from($this->getEntityName(), 'e')
            ->join('e.translations', 't', Join::WITH, 't.locale = :locale')
            ->orderBy('date', 'DESC')
            ->setParameter('locale', $locale)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
