<?php

namespace AppBundle\Twig;

use JsonSerializable;
use Twig_Extension;
use Twig_SimpleFilter;

class EventsExtension extends Twig_Extension
{
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('eventTemplateName', [$this, 'getEventTemplateNameFilter'])
        ];
    }

    public function getEventTemplateNameFilter(JsonSerializable $model)
    {
        $fqcn = explode('\\', get_class($model));

        return mb_strtolower(end($fqcn));
    }
}
