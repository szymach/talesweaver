<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Form\Event\SceneEvents;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;

class OptionsListController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(SceneResolver $sceneResolver, ApiResponseFactoryInterface $responseFactory)
    {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        return $this->responseFactory->list(
            'scene/events/options.html.twig',
            ['sceneId' => $scene->getId(), 'eventModels' => SceneEvents::getAllEvents()]
        );
    }
}
