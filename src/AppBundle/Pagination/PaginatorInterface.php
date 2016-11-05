<?php

namespace AppBundle\Pagination;

use Pagerfanta\Pagerfanta;

/**
 * @author Piotr Szymaszek
 */
interface PaginatorInterface
{
    public function getResults(int $page = 1, int $maxPerPage = 10) : Pagerfanta;
}
