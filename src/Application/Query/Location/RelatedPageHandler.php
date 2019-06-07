<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Locations;

final class RelatedPageHandler implements QueryHandlerInterface
{
    /**
     * @var Locations
     */
    private $locations;

    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

    public function __invoke(RelatedPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter($this->locations->findRelated($query->getScene()))
        );
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
