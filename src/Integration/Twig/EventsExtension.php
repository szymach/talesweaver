<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Twig;

use JsonSerializable;
use ReflectionClass;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class EventsExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('eventTemplateName', function (JsonSerializable $model): string {
                return sprintf(
                    'scene/events/models/%s.html.twig',
                    mb_strtolower((new ReflectionClass($model))->getShortName())
                );
            })
        ];
    }
}
