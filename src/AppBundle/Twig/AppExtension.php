<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Event;
use AppBundle\JSON\EventParser;
use JsonSerializable;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class AppExtension extends Twig_Extension
{
    /**
     * @var EventParser
     */
    private $eventParser;

    public function __construct(EventParser $eventParser)
    {
        $this->eventParser = $eventParser;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('eventModel', [$this, 'getEventModelFunction'])
        ];
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('eventTemplateName', [$this, 'getEventTemplateNameFilter'])
        ];
    }

    public function getEventModelFunction(Event $event)
    {
        return $this->eventParser->parse($event);
    }

    public function getEventTemplateNameFilter(JsonSerializable $model)
    {
        $fqcn = explode('\\', get_class($model));

        return mb_strtolower(end($fqcn));
    }
}
