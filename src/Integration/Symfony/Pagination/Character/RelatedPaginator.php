<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Pagination\Character;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\CharacterRepository;

class RelatedPaginator
{
    /**
     * @var CharacterRepository
     */
    private $repository;

    public function __construct(CharacterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(Scene $scene, int $page = 1, int $maxPerPage = 10): Pagerfanta
    {
        $pager = new Pagerfanta(new ArrayAdapter($this->repository->findRelated($scene)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}