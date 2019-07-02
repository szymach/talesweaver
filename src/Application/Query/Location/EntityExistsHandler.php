<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Locations;

final class EntityExistsHandler implements QueryHandlerInterface
{
    /**
     * @var Locations
     */
    private $locations;

    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

    public function __invoke(EntityExists $query): bool
    {
        return $this->locations->entityExists($query->getName(), $query->getId(), $query->getScene());
    }
}
