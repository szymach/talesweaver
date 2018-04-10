<?php

declare(strict_types=1);

namespace App\Pagination\Chapter;

use Domain\Entity\Chapter;
use App\Repository\SceneRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

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
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->repository->createForChapterQb($chapter)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
