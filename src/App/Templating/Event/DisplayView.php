<?php

declare(strict_types=1);

namespace App\Templating\Event;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Domain\Entity\Event;
use Symfony\Component\HttpFoundation\JsonResponse;

class DisplayView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
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
