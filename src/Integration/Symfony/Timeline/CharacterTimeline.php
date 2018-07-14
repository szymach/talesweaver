<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Timeline;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Integration\Symfony\Repository\SceneRepository;

class CharacterTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id): array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstCharacterOccurence($id)];
    }
}
