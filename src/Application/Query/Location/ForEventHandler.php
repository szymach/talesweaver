<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Locations;

final class ForEventHandler implements QueryHandlerInterface
{
    /**
     * @var Locations
     */
    private $locations;

    public function __construct(Locations $characters)
    {
        $this->locations = $characters;
    }

    public function __invoke(ForEvent $query): array
    {
        return $this->locations->findForEvent($query->getScene());
    }
}
