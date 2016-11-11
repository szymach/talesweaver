<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\Repository;

class ChapterRepository extends TranslatableRepository
{
    public function createStandaloneQb()
    {
        return $this->createQueryBuilder('c')->where('c.book IS NULL');
    }

    public function findLatestStandalone()
    {
        return $this->createStandaloneQb()
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
}
