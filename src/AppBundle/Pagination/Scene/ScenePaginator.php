<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Repository\SceneRepository;
use AppBundle\Pagination\Paginator;
use Pagerfanta\Pagerfanta;

/**
 * @property SceneRepository $repository
 */
class ScenePaginator extends Paginator
{
    public function __construct(SceneRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $page
     * @return Pagerfanta
     */
    public function getStandalone(int $page) : Pagerfanta
    {
        return $this->getResults($this->repository->createStandaloneQb(), $page);
    }

    /**
     * @param Chapter $book
     * @param int $page
     * @return QueryBuilder
     */
    public function getForChapter(Chapter $book, int $page) : Pagerfanta
    {
        return $this->getResults($this->repository->createForChapterQb($book), $page);
    }
}
