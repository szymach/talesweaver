<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Positionable\IncreaseSingle\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;

final class IncreasePositionController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    public function __construct(
        SceneResolver $sceneResolver,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $apiResponseFactory
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->commandBus = $commandBus;
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $this->commandBus->dispatch(new Command($scene));

        return $this->apiResponseFactory->success();
    }
}
