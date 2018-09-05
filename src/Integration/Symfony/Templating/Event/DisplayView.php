<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Event;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Event;

class DisplayView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $templating;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
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
