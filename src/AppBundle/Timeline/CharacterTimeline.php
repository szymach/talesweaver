<?php

namespace AppBundle\Timeline;

use AppBundle\Entity\Repository\SceneRepository;
use Ramsey\Uuid\UuidInterface;

class CharacterTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id) : array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstCharacterOccurence($id)];
    }
}
