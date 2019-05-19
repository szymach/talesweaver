<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Character\AddToScene\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\CharacterResolver;
use Talesweaver\Application\Http\Entity\SceneResolver;

class AddToSceneController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var CharacterResolver
     */
    private $characterResolver;

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
        CharacterResolver $characterResolver,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $responseFactory
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->characterResolver = $characterResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(new Command(
            $this->sceneResolver->fromRequest($request, 'scene_id'),
            $this->characterResolver->fromRequest($request, 'id')
        ));

        return $this->responseFactory->success();
    }
}
