<?php

declare(strict_types=1);

namespace Integration\Twig;

use JsonSerializable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use function mb_strtolower;

class EventsExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('eventTemplateName', function (JsonSerializable $model): string {
                $fqcn = explode('\\', get_class($model));

                return sprintf('scene/events/%s.html.twig', mb_strtolower(end($fqcn)));
            })
        ];
    }
}
