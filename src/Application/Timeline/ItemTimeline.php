<?php

declare(strict_types=1);

namespace Talesweaver\Application\Timeline;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Scenes;

class ItemTimeline extends TimelineFormatter
{
    protected function getCreation(Scenes $scenes, UuidInterface $id): array
    {
        return ['fa fa-user-plus' => $scenes->firstItemOccurence($id)];
    }
}
