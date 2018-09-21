<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Item;
use Talesweaver\Integration\Symfony\Timeline\ItemTimeline;

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
     * @var ItemTimeline
     */
    private $timeline;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        ItemTimeline $timeline
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->timeline = $timeline;
    }

    public function __invoke(Item $item): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'display' => $this->htmlContent->fromTemplate(
                'scene\items\display.html.twig',
                [
                    'item' => $item,
                    'timeline' => $this->timeline->getTimeline($item->getId(), Item::class)
                ]
            )
        ]);
    }
}
