<?php

namespace AppBundle\Pagination\Book;

use AppBundle\Entity\Repository\BookRepository;
use AppBundle\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class StandalonePaginator extends Paginator
{
    /**
     * @var BookRepository
     */
    private $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->repository->createQueryBuilder('b');
    }
}
