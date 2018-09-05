<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Integration\Symfony\Timeline\CharacterTimeline;

class DisplayController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var CharacterTimeline
     */
    private $timeline;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        CharacterTimeline $timeline
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->timeline = $timeline;
    }

    public function __invoke(Character $character): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'display' => $this->htmlContent->fromTemplate(
                'scene\characters\display.html.twig',
                [
                    'character' => $character,
                    'timeline' => $this->timeline->getTimeline($character->getId(), Character::class)
                ]
            )
        ]);
    }
}
