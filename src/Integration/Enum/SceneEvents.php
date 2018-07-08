<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Enum;

use InvalidArgumentException;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Integration\Form\Event\MeetingType;

final class SceneEvents
{
    public const MEETING = Meeting::class;

    public static function isEvent(string $model): void
    {
        if (false === in_array($model, [self::MEETING], true)) {
            throw new InvalidArgumentException(sprintf('%s is not a scene event model!', $model));
        }
    }

    public static function getAllEvents(): array
    {
        return array_keys(self::getAllForms());
    }

    public static function getAllForms(): array
    {
        return [self::MEETING => MeetingType::class];
    }

    public static function getEventForm(string $model): string
    {
        self::isEvent($model);

        return self::getAllForms()[$model];
    }

    private function __construct()
    {
    }
}
