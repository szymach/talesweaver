<?php

declare(strict_types=1);

namespace Talesweaver\Application\Timeline;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Events;
use Talesweaver\Domain\Scenes;
use function mb_strtolower;

abstract class TimelineFormatter
{
    private const EVENT_ICONS = [
        Meeting::class => 'fa fa-users'
    ];

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

    public function getTimeline(UuidInterface $id, string $class): array
    {
        return array_merge(
            [sprintf('event.timeline.creation.%s', $class) => $this->getCreation($this->scenes, $id)],
            $this->formatEvents($this->events->findInEventsById($id))
        );
    }

    abstract protected function getCreation(Scenes $scenes, UuidInterface $id): array;

    private function formatEvents(array $events): array
    {
        return array_reduce($events, function (array $initial, Event $event): array {
            $model = $event->getModel();
            $modelClass = get_class($model);
            $fqcn = explode('\\', $modelClass);
            $initial[sprintf('event.%s.name', $modelClass)] = [
                self::EVENT_ICONS[$modelClass] => $this->htmlContent->fromTemplate(
                    sprintf('scene/events/%s.html.twig', mb_strtolower(end($fqcn))),
                    ['model' => $model]
                )
            ];

            return $initial;
        }, []);
    }
}
