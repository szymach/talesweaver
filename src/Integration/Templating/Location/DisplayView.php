<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating\Location;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Location;
use Talesweaver\Integration\Timeline\LocationTimeline;

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
