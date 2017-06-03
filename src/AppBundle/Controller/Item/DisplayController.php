<?php

namespace AppBundle\Controller\Item;

use AppBundle\Entity\Item;
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

    public function __invoke(Item $item)
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\items\display.html.twig',
                ['item' => $item]
            )
        ]);
    }
}
