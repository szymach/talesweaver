<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Pagination\Item;

use Talesweaver\Integration\Repository\ItemRepository;
use Talesweaver\Domain\Scene;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class ItemPaginator
{
    /**
     * @var ItemRepository
     */
    private $repository;

    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(Scene $scene, int $page = 1, int $maxPerPage = 3): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->repository->createForSceneQueryBuilder($scene)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
