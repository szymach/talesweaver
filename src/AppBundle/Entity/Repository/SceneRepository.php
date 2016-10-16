<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\Repository;

class SceneRepository extends TranslatableRepository
{
    public function createPaginatedQb($page)
    {
        return $this->createTranslatableQueryBuilder('s');
    }
}