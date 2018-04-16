<?php

declare(strict_types=1);

namespace App\Templating\Event;

use App\Enum\SceneEvents;
use App\Templating\Engine;
use Domain\Entity\Scene;
use Symfony\Component\HttpFoundation\JsonResponse;

class OptionsListView
{
    /**
     * @var Engine
     */
    private $templating;

    public function __construct(Engine $templating)
    {
        $this->templating = $templating;
    }

    public function createView(Scene $scene): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene/events/options.html.twig',
                ['sceneId' => $scene->getId(), 'eventModels' => SceneEvents::getAllEvents()]
            )
        ]);
    }
}
