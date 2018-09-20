<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Integration\Symfony\Pagination\Location\LocationPaginator;

class LocationsPageHandler implements QueryHandlerInterface
{
    /**
     * @var LocationPaginator
     */
    private $pagination;

    public function __construct(LocationPaginator $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke(LocationsPage $query): Pagerfanta
    {
        return $this->pagination->getResults($query->getScene(), $query->getPage());
    }
}
