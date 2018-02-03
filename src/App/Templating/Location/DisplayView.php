<?php

declare(strict_types=1);

namespace App\Templating\Location;

use Domain\Entity\Location;
use App\Templating\Engine;
use App\Timeline\LocationTimeline;
use Symfony\Component\HttpFoundation\JsonResponse;

class DisplayView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var LocationTimeline
     */
    private $timeline;

    public function __construct(
        Engine $templating,
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
