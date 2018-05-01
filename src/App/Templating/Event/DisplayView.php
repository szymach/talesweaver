<?php

declare(strict_types=1);

namespace App\Templating\Event;

use App\Templating\Engine;
use Domain\Entity\Event;
use Symfony\Component\HttpFoundation\JsonResponse;

class DisplayView
{
    /**
     * @var Engine
     */
    private $templating;

    public function __construct(Engine $templating)
    {
        $this->templating = $templating;
    }

    public function createView(Event $event): JsonResponse
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\events\display.html.twig',
                ['event' => $event]
            )
        ]);
    }
}
