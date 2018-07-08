<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Timeline;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Integration\Repository\SceneRepository;

class LocationTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id): array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstLocationOccurence($id)];
    }
}
