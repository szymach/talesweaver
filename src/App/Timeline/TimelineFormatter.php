<?php

declare(strict_types=1);

namespace App\Timeline;

use App\Repository\EventRepository;
use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Domain\Event;
use Domain\Event\Meeting;
use Ramsey\Uuid\UuidInterface;
use function mb_strtolower;

abstract class TimelineFormatter
{
    /**
     * @var SceneRepository
     */
    private $sceneRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var array
     */
    private $eventIcons = [
        Meeting::class => 'fa fa-users'
    ];

    public function __construct(
        SceneRepository $sceneRepository,
        EventRepository $eventRepository,
        EngineInterface $templating
    ) {
        $this->sceneRepository = $sceneRepository;
        $this->eventRepository = $eventRepository;
        $this->templating = $templating;
    }

    public function getTimeline(UuidInterface $id, string $class): array
    {
        return array_merge(
            [sprintf('event.timeline.creation.%s', $class) => $this->getCreation($this->sceneRepository, $id)],
            $this->formatEvents($this->eventRepository->findInEventsById($id))
        );
    }

    abstract protected function getCreation(SceneRepository $sceneRepository, UuidInterface $id): array;

    private function formatEvents(array $events): array
    {
        return array_reduce($events, function (array $initial, Event $event): array {
            $model = $event->getModel();
            $modelClass = get_class($model);
            $fqcn = explode('\\', $modelClass);
            $initial[sprintf('event.%s.name', $modelClass)] = [
                $this->eventIcons[$modelClass] => $this->templating->render(
                    sprintf('scene/events/%s.html.twig', mb_strtolower(end($fqcn))),
                    ['model' => $model]
                )
            ];

            return $initial;
        }, []);
    }
}
