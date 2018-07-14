<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Event;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Event;

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
