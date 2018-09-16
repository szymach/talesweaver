<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Item;

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

    public function __construct(ResponseFactoryInterface $responseFactory, HtmlContent $htmlContent)
    {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
    }

    public function createView(Item $event): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'display' => $this->htmlContent->fromTemplate(
                'scene\events\display.html.twig',
                ['event' => $event]
            )
        ]);
    }
}
