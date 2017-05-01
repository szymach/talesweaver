<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Enum;

use AppBundle\Event\Meeting;
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

    public function getAllEvents()
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
