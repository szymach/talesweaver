<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Item\AddToScene\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ItemResolver;
use Talesweaver\Application\Http\Entity\SceneResolver;

class AddToSceneController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var ItemResolver
     */
    private $itemResolver;

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
        ItemResolver $itemResolver,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $responseFactory
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->itemResolver = $itemResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(new Command(
            $this->sceneResolver->fromRequest($request, 'scene_id'),
            $this->itemResolver->fromRequest($request, 'id')
        ));

        return $this->responseFactory->success();
    }
}
