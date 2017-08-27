<?php

namespace AppBundle\Location;

use AppBundle\Entity\Repository\SceneRepository;
use AppBundle\Event\TimelineFormatter;
use Ramsey\Uuid\UuidInterface;

class LocationTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id) : array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstLocationOccurence($id)];
    }
}
