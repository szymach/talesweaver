<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\Entity\EventResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class DisplayController
{
    /**
     * @var EventResolver
     */
    private $eventResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(
        EventResolver $eventResolver,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent
    ) {
        $this->eventResolver = $eventResolver;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'display' => $this->htmlContent->fromTemplate(
                'scene\events\display.html.twig',
                ['event' => $this->eventResolver->fromRequest($request)]
            )
        ]);
    }
}
