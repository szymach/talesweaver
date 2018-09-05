<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Event;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Enum\SceneEvents;

class OptionsListView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $templating;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
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
