<?php

namespace AppBundle\Templating\Event;

use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Pagination\EventPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var EventPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, EventPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, $page) : JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\events\list.html.twig',
                [
                    'events' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'eventModels' => SceneEvents::getAllEvents(),
                    'page' => $page
                ]
            )
        ]);
    }
}
