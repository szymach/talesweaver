<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Locations;

final class LocationsPageHandler implements QueryHandlerInterface
{
    /**
     * @var Locations
     */
    private $locations;

    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

    public function __invoke(LocationsPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter($this->locations->findForScene($query->getScene()))
        );
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
