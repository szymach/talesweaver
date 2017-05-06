<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Location;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class DisplayController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function displayAction(Location $location)
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\locations\display.html.twig',
                ['location' => $location]
            )
        ]);
    }

}
