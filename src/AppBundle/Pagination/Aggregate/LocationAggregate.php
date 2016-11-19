<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Pagination\Aggregate;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Scene\LocationPaginator as ForScenePaginator;

class LocationAggregate
{
    /**
     * @var ForScenePaginator
     */
    private $forScenePaginator;

    public function __construct(ForScenePaginator $forScenePaginator)
    {
        $this->forScenePaginator = $forScenePaginator;
    }

    /**
     * @param Scene $forScene
     * @return Pagerfanta
     */
    public function getForScene(Scene $forScene)
    {
        return $this->forScenePaginator->getForSceneResults($forScene);
    }
}
