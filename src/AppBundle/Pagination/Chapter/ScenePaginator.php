<?php

namespace AppBundle\Pagination\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Repository\SceneRepository;
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

    public function getResults(Chapter $book, int $page, int $maxPerPage = 10) : Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->repository->createForChapterQb($book)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
