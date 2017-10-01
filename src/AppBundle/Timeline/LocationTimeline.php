<?php

namespace AppBundle\Timeline;

use AppBundle\Repository\SceneRepository;
use Ramsey\Uuid\UuidInterface;

class LocationTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id) : array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstLocationOccurence($id)];
    }
}
