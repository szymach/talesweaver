<?php

declare(strict_types=1);

namespace AppBundle\Templating\Item;

use AppBundle\Entity\Item;
use AppBundle\Timeline\ItemTimeline;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DisplayView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ItemTimeline
     */
    private $timeline;

    public function __construct(
        EngineInterface $templating,
        ItemTimeline $timeline
    ) {
        $this->templating = $templating;
        $this->timeline = $timeline;
    }

    public function createView(Item $item): JsonResponse
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\items\display.html.twig',
                [
                    'item' => $item,
                    'timeline' => $this->timeline->getTimeline($item->getId(), Item::class)
                ]
            )
        ]);
    }
}
