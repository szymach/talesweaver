<?php

declare(strict_types=1);

namespace Talesweaver\Application\Timeline;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Domain\Events;
use Talesweaver\Domain\Scenes;

abstract class TimelineFormatter
{
    /**
     * @var Scenes
     */
    private $scenes;

    /**
     * @var Events
     */
    private $events;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(Scenes $scenes, Events $events, HtmlContent $htmlContent)
    {
        $this->scenes = $scenes;
        $this->events = $events;
        $this->htmlContent = $htmlContent;
    }

    public function getTimeline(UuidInterface $id): array
    {
        return ['event.timeline.creation' => $this->getCreation($this->scenes, $id)];
    }

    abstract protected function getCreation(Scenes $scenes, UuidInterface $id): array;
}
