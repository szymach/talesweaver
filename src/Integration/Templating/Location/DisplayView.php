<?php

declare(strict_types=1);

namespace Integration\Templating\Location;

use Domain\Location;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Integration\Timeline\LocationTimeline;
use Symfony\Component\HttpFoundation\JsonResponse;

class DisplayView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var LocationTimeline
     */
    private $timeline;

    public function __construct(
        EngineInterface $templating,
        LocationTimeline $timeline
    ) {
        $this->templating = $templating;
        $this->timeline = $timeline;
    }

    public function createView(Location $location): JsonResponse
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\locations\display.html.twig',
                [
                    'location' => $location,
                    'timeline' => $this->timeline->getTimeline($location->getId(), Location::class)
                ]
            )
        ]);
    }
}
