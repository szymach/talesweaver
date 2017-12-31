<?php

declare(strict_types=1);

namespace App\Templating\Item;

use App\Entity\Item;
use App\Timeline\ItemTimeline;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\JsonResponse;

class DisplayView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var ItemTimeline
     */
    private $timeline;

    public function __construct(
        Engine $templating,
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
