<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Pagination\EventPaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListController
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

    public function __invoke(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\events\list.html.twig',
                [
                    'events' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene,
                    'eventModels' => SceneEvents::getAllEvents(),
                    'page' => $page
                ]
            )
        ]);
    }
}
