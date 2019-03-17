<?php

declare(strict_types=1);

namespace Talesweaver\Application\Form\Event;

use InvalidArgumentException;
use Talesweaver\Domain\Event\Death;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Application\Form\Type;

final class SceneEvents
{
    public const ALL_FORMS = [
        Meeting::class => Type\Event\Meeting::class,
        Death::class => Type\Event\Death::class
    ];

    public static function isEvent(string $model): void
    {
        if (false === in_array($model, self::getAllEvents(), true)) {
            throw new InvalidArgumentException(sprintf('%s is not a scene event model!', $model));
        }
    }

    public static function getAllEvents(): array
    {
        return array_keys(self::ALL_FORMS);
    }

    public static function getEventForm(string $model): string
    {
        self::isEvent($model);

        return self::ALL_FORMS[$model];
    }

    private function __construct()
    {
    }
}
