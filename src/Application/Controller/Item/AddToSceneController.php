<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Item\AddToScene\Command;
use Talesweaver\Application\Http\Entity\ItemResolver;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        SceneResolver $sceneResolver,
        ItemResolver $itemResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
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
            $this->itemResolver->fromRequest($request, 'item_id')
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
