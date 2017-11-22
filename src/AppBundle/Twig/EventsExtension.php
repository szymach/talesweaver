<?php

declare(strict_types=1);

namespace AppBundle\Twig;

use JsonSerializable;
use Twig_Extension;
use Twig_SimpleFilter;

class EventsExtension extends Twig_Extension
{
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('eventTemplateName', function (JsonSerializable $model): string {
                $fqcn = explode('\\', get_class($model));

                return sprintf('scene/events/%s.html.twig', mb_strtolower(end($fqcn)));
            })
        ];
    }
}
