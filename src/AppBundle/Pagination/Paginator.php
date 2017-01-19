<?php

namespace AppBundle\Pagination;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class Paginator
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @param QueryBuilder $qb
     * @param int $page
     * @param int $maxPerPage
     * @return Pagerfanta
     */
    protected function getResults(QueryBuilder $qb, int $page = 1, int $maxPerPage = 10) : Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
