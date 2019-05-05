<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Locations;

final class ByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Locations
     */
    private $locations;

    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

    public function __invoke(ById $query): ?Location
    {
        return $this->locations->find($query->getId());
    }
}
