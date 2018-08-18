<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Pagination\Chapter;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Integration\Symfony\Repository\ChapterRepository;

class ChapterPaginator
{
    /**
     * @var ChapterRepository
     */
    private $repository;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(int $page, int $maxPerPage = 10): Pagerfanta
    {
        $pager = new Pagerfanta(new ArrayAdapter($this->repository->findStandalone()));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
