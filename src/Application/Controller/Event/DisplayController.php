<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\EventResolver;

class DisplayController
{
    /**
     * @var EventResolver
     */
    private $eventResolver;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        EventResolver $eventResolver,
        ApiResponseFactoryInterface $responseFactory
    ) {
        $this->eventResolver = $eventResolver;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->display(
            'scene\events\display.html.twig',
            ['event' => $this->eventResolver->fromRequest($request)]
        );
    }
}
