<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Characters;
use Talesweaver\Domain\Events;
use Talesweaver\Domain\Items;
use Talesweaver\Domain\Locations;

final class SceneSummaryHandler implements QueryHandlerInterface
{
    /**
     * @var Characters
     */
    private $characters;

    /**
     * @var Locations
     */
    private $locations;

    /**
     * @var Items
     */
    private $items;

    /**
     * @var Events
     */
    private $events;

    public function __construct(Characters $characters, Locations $locations, Items $items, Events $events)
    {
        $this->characters = $characters;
        $this->locations = $locations;
        $this->items = $items;
        $this->events = $events;
    }

    public function __invoke(SceneSummary $query): array
    {
        $scene = $query->getScene();
        return [
            'characters' => $this->characters->findNamesForScene($scene),
            'locations' => $this->locations->findNamesForScene($scene),
            'items' => $this->items->findNamesForScene($scene),
            'events' => $this->events->findNamesForScene($scene)
        ];
    }
}
