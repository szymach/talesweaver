<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Event;

use Talesweaver\Application\Form\Event\EventModelResolver as ApplicationEventModelResolver;
use Talesweaver\Application\Form\Event\SceneEvents;
use Talesweaver\Integration\Symfony\Form\FormClassResolver;

class EventModelResolver implements ApplicationEventModelResolver
{
    /**
     * @var FormClassResolver
     */
    private $formClassResolver;

    public function __construct(FormClassResolver $formClassResolver)
    {
        $this->formClassResolver = $formClassResolver;
    }

    public function resolve(string $model): string
    {
        return $this->formClassResolver->resolve(SceneEvents::getEventForm($model));
    }
}
