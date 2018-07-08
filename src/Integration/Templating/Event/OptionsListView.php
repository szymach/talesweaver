<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating\Event;

use Talesweaver\Integration\Enum\SceneEvents;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Talesweaver\Domain\Scene;
use Symfony\Component\HttpFoundation\JsonResponse;

class OptionsListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
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
