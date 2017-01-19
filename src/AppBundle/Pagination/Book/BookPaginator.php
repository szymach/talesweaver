<?php

namespace AppBundle\Pagination\Book;

use AppBundle\Entity\Repository\BookRepository;
use AppBundle\Pagination\Paginator;
use Pagerfanta\Pagerfanta;

/**
 * @property BookRepository $repository
 */
class BookPaginator extends Paginator
{
    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $page
     * @return Pagerfanta
     */
    public function getStandalone(int $page) : Pagerfanta
    {
        return $this->getResults($this->repository->createQueryBuilder('b'), $page);
    }
}
