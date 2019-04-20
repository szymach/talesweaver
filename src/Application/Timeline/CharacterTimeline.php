<?php

declare(strict_types=1);

namespace Talesweaver\Application\Timeline;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Scenes;

class CharacterTimeline extends TimelineFormatter
{
    protected function getCreation(Scenes $scenes, UuidInterface $id): array
    {
        return ['user-plus' => $scenes->firstCharacterOccurence($id)];
    }
}
