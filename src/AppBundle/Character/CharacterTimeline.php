<?php

namespace AppBundle\Character;

use AppBundle\Entity\Repository\SceneRepository;
use AppBundle\Event\TimelineFormatter;
use Ramsey\Uuid\UuidInterface;

class CharacterTimeline extends TimelineFormatter
{
    protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id) : array
    {
        return ['fa fa-user-plus' => $sceneRepository->firstCharacterOccurence($id)];
    }
}
