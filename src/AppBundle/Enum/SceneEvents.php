<?php

namespace AppBundle\Enum;

use AppBundle\Model\Meeting;
use AppBundle\Form\Event\MeetingType;
use InvalidArgumentException;

final class SceneEvents
{
    const MEETING = Meeting::class;

    public static function isEvent(string $model)
    {
        if (!in_array($model, [self::MEETING])) {
            throw new InvalidArgumentException(sprintf('%s is not a scene event model!', $model));
        }
    }

    public static function getAllEvents()
    {
        return array_keys(self::getAllForms());
    }

    public static function getAllForms()
    {
        return [self::MEETING => MeetingType::class];
    }

    public static function getEventForm($model)
    {
        self::isEvent($model);

        return self::getAllForms()[$model];
    }

    private function __construct()
    {
    }
}
