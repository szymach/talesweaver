<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Event;

use Talesweaver\Domain\Location;

final class LocationView
{
    /**
     * @var Location
     */
    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
}
