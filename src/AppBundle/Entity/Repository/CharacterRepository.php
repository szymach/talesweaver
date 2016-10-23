<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Scene;

class CharacterRepository extends TranslatableRepository
{
    public function getForScene(Scene $scene)
    {
        return $this->createTranslatableQueryBuilder('c')
            ->andWhere(':scene MEMBER OF c.scenes')
            ->setParameter('scene', $scene)
            ->getQuery()
            ->getResult()
        ;
    }
}