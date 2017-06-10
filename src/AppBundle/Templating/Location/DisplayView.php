<?php

namespace AppBundle\Templating\Location;

use AppBundle\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
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

    public function createView(Location $location) : JsonResponse
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\locations\display.html.twig',
                ['location' => $location]
            )
        ]);
    }
}
