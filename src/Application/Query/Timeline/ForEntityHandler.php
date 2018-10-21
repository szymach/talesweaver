<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Timeline;

use RuntimeException;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Application\Timeline\CharacterTimeline;
use Talesweaver\Application\Timeline\ItemTimeline;
use Talesweaver\Application\Timeline\LocationTimeline;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;

class ForEntityHandler implements QueryHandlerInterface
{
    /**
     * @var CharacterTimeline
     */
    private $characterTimeline;

    /**
     * @var ItemTimeline
     */
    private $itemTimeline;

    /**
     * @var LocationTimeline
     */
    private $locationTimeline;

    public function __construct(
        CharacterTimeline $characterTimeline,
        ItemTimeline $itemTimeline,
        LocationTimeline $locationTimeline
    ) {
        $this->characterTimeline = $characterTimeline;
        $this->itemTimeline = $itemTimeline;
        $this->locationTimeline = $locationTimeline;
    }

    public function __invoke(ForEntity $query): array
    {
        $id = $query->getId();
        switch ($query->getClass()) {
            case Character::class:
                $result = $this->characterTimeline->getTimeline($id, Character::class);
                break;
            case Item::class:
                $result = $this->itemTimeline->getTimeline($id, Item::class);
                break;
            case Location::class:
                $result = $this->locationTimeline->getTimeline($id, Location::class);
                break;
            default:
                throw new RuntimeException(
                    sprintf('No timeline for entity "%s"', $query->getClass())
                );
        }

        return $result;
    }
}