<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating\Character;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Character;
use Talesweaver\Integration\Timeline\CharacterTimeline;

class DisplayView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var CharacterTimeline
     */
    private $timeline;

    public function __construct(
        EngineInterface $templating,
        CharacterTimeline $timeline
    ) {
        $this->templating = $templating;
        $this->timeline = $timeline;
    }

    public function createView(Character $character): JsonResponse
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\characters\display.html.twig',
                [
                    'character' => $character,
                    'timeline' => $this->timeline->getTimeline($character->getId(), Character::class)
                ]
            )
        ]);
    }
}
