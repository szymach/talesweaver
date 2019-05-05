<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Locations;

final class ForSceneHandler implements QueryHandlerInterface
{
    /**
     * @var Locations
     */
    private $locations;

    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

    public function __invoke(ForScene $query): array
    {
        return $this->locations->findForScene($query->getScene());
    }
}
