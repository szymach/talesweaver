<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Location\AddToScene\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\LocationResolver;
use Talesweaver\Application\Http\Entity\SceneResolver;

class AddToSceneController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var LocationResolver
     */
    private $locationResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        SceneResolver $sceneResolver,
        LocationResolver $locationResolver,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $responseFactory
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->locationResolver = $locationResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(new Command(
            $this->sceneResolver->fromRequest($request, 'scene_id'),
            $this->locationResolver->fromRequest($request, 'id')
        ));

        return $this->responseFactory->success();
    }
}
