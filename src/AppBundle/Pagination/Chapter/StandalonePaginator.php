<?php

namespace AppBundle\Pagination\Chapter;

use AppBundle\Entity\Repository\ChapterRepository;
use AppBundle\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class StandalonePaginator extends Paginator
{
    /**
     * @var ChapterRepository
     */
    private $repository;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->repository->createStandaloneQb();
    }
}
