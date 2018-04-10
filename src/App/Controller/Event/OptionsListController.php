<?php

declare(strict_types=1);

namespace App\Controller\Event;

use App\Enum\SceneEvents;
use App\Templating\Engine;
use Domain\Entity\Scene;
use Symfony\Component\HttpFoundation\JsonResponse;

class OptionsListController
{
    /**
     * @var Engine
     */
    private $templating;

    public function __construct(Engine $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Scene $scene)
    {
        return new JsonResponse([
            'display' => $this->templating->render('scene/events/options.html.twig', ['sceneId' => $scene->getId(),
                    'eventModels' => SceneEvents::getAllEvents()])
        ]);
    }
}
