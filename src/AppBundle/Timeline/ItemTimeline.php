<?php

namespace AppBundle\Timeline;

use AppBundle\Entity\Repository\SceneRepository;
use Ramsey\Uuid\UuidInterface;

class ItemTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id) : array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstItemOccurence($id)];
    }
}
