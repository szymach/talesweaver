<?php

namespace AppBundle\Character;

use AppBundle\Entity\Character;
use AppBundle\Entity\Event;
use AppBundle\Entity\Repository\EventRepository;
use AppBundle\Entity\Repository\SceneRepository;
use Symfony\Component\Templating\EngineInterface;

class TimelineFormatter
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

    public function __construct(
        SceneRepository $sceneRepository,
        EventRepository $eventRepository,
        EngineInterface $templating
    ) {
        $this->sceneRepository = $sceneRepository;
        $this->eventRepository = $eventRepository;
        $this->templating = $templating;
    }

    public function getTimeline(Character $character)
    {
        $creation = $this->sceneRepository->firstCharacterOccurence($character);

        return array_merge(
            ['character.timeline.creation' => ['fa fa-user-plus' => $creation]],
            $this->formatEvents($this->eventRepository->findForCharacter($character))
        );
    }

    /**
     * @param Event[] $events
     */
    private function formatEvents(array $events)
    {
        return array_reduce($events, function (array $initial, Event $event) {
            $model = $event->getModel();
            $fqcn = explode('\\', get_class($model));
            $template = sprintf(
                'scene/events/%s.html.twig',
                mb_strtolower(end($fqcn))
            );
            $label = sprintf('event.%s.name', get_class($model));
            $initial[$label] = [
                'fa fa-users' => $this->templating->render($template, ['model' => $model])
            ];

            return $initial;
        }, []);
    }
}
