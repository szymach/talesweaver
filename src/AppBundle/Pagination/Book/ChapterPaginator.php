<?php

namespace AppBundle\Pagination\Book;

use AppBundle\Entity\Book;
use AppBundle\Entity\Repository\ChapterRepository;
use AppBundle\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class ChapterPaginator extends Paginator
{
    /**
     * @var ChapterRepository
     */
    private $repository;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getForBookResults(Book $book, $page = 1, $maxPerPage = 10)
    {
        $this->queryBuilder = $this->repository->createForBookQb($book);
        return $this->getResults($page, $maxPerPage);
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->queryBuilder;
    }
}
