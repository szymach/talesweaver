<?php

namespace AppBundle\Pagination\Chapter;

use AppBundle\Entity\Book;
use AppBundle\Entity\Repository\ChapterRepository;
use AppBundle\Pagination\Paginator;
use Pagerfanta\Pagerfanta;

/**
 * @property ChapterRepository $repository
 */
class ChapterPaginator extends Paginator
{
    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $page
     * @return Pagerfanta
     */
    public function getStandalone(int $page) : Pagerfanta
    {
        return $this->getResults($this->repository->createQueryBuilder('c'), $page);
    }

    /**
     * @param Book $book
     * @param int $page
     * @return QueryBuilder
     */
    public function getForBook(Book $book, int $page) : Pagerfanta
    {
        return $this->getResults($this->repository->createForBookQb($book), $page);
    }
}
