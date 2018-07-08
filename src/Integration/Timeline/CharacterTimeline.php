<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Timeline;

use Talesweaver\Integration\Repository\SceneRepository;
use Ramsey\Uuid\UuidInterface;

class CharacterTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id): array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstCharacterOccurence($id)];
    }
}
