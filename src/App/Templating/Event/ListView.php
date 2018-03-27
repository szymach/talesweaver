<?php

declare(strict_types=1);

namespace App\Templating\Event;

use App\Enum\SceneEvents;
use App\Pagination\EventPaginator;
use App\Templating\Engine;
use Domain\Entity\Scene;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var EventPaginator
     */
    private $pagination;

    public function __construct(Engine $templating, EventPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, int $page): JsonResponse
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
