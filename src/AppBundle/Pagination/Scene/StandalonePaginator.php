<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Repository\SceneRepository;
use AppBundle\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class StandalonePaginator extends Paginator
{
    /**
     * @var SceneRepository
     */
    private $repository;

    public function __construct(SceneRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->repository->createStandaloneQb();
    }
}
