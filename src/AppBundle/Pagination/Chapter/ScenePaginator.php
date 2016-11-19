<?php

namespace AppBundle\Pagination\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Repository\SceneRepository;
use AppBundle\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class ScenePaginator extends Paginator
{
    /**
     * @var SceneRepository
     */
    private $repository;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct(SceneRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getForChapterResults(Chapter $chapter, $page = 1, $maxPerPage = 10)
    {
        $this->queryBuilder = $this->repository->createForChapterQb($chapter);
        return $this->getResults($page, $maxPerPage);
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->queryBuilder;
    }
}
