<?php

declare(strict_types=1);

namespace AppBundle\Timeline;

use AppBundle\Repository\SceneRepository;
use Ramsey\Uuid\UuidInterface;

class ItemTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id): array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstItemOccurence($id)];
    }
}
