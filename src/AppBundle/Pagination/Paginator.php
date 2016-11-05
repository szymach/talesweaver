<?php

namespace AppBundle\Pagination;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class Paginator implements PaginatorInterface
{
    /**
     * @param int $page
     * @param int $maxPerPage
     * @return Pagerfanta
     */
    public function getResults(int $page = 1, int $maxPerPage = 10) : Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->getQueryBuilder()));
        $pager->setCurrentPage($page);
        $pager->setMaxPerPage($maxPerPage);

        return $pager;
    }

    /**
     * @return QueryBuilder
     */
    abstract protected function getQueryBuilder() : QueryBuilder;
}
