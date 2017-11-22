<?php

declare(strict_types=1);

namespace AppBundle\Pagination\Book;

use AppBundle\Repository\BookRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class BookPaginator
{
    /**
     * @var BookRepository
     */
    private $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(int $page = 1, int $maxPerPage = 10): Pagerfanta
    {
        $pager = new Pagerfanta(
            new DoctrineORMAdapter($this->repository->createQueryBuilder())
        );
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
