<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class DisplayController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        SceneResolver $sceneResolver,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'scene/display.html.twig',
            ['scene' => $this->sceneResolver->fromRequest($request)]
        );
    }
}
