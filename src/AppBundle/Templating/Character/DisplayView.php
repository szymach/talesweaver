<?php

namespace AppBundle\Templating\Character;

use AppBundle\Character\TimelineFormatter;
use AppBundle\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DisplayView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var TimelineFormatter
     */
    private $timeline;

    public function __construct(
        EngineInterface $templating,
        TimelineFormatter $timeline
    ) {
        $this->templating = $templating;
        $this->timeline = $timeline;
    }

    public function createView(Character $character) : JsonResponse
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\characters\display.html.twig',
                [
                    'character' => $character,
                    'timeline' => $this->timeline->getTimeline($character)
                ]
            )
        ]);
    }
}
