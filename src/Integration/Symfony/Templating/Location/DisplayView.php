<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Location;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Location;
use Talesweaver\Integration\Symfony\Timeline\LocationTimeline;

class DisplayView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $templating;

    /**
     * @var LocationTimeline
     */
    private $timeline;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        LocationTimeline $timeline
    ) {
        $this->responseFactory = $responseFactory;
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
