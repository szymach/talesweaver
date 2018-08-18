<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Pagination\Chapter;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Repository\SceneRepository;

class ScenePaginator
{
    /**
     * @var SceneRepository
     */
    private $repository;

    public function __construct(SceneRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(Chapter $chapter, int $page, int $maxPerPage = 8): Pagerfanta
    {
        $pager = new Pagerfanta(new ArrayAdapter($this->repository->findForChapter($chapter)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
