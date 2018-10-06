<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Character\RemoveFromScene\Command;
use Talesweaver\Application\Http\Entity\CharacterResolver;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class RemoveFromSceneController
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        SceneResolver $sceneResolver,
        CharacterResolver $characterResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
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
            $this->characterResolver->fromRequest($request, 'character_id')
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
