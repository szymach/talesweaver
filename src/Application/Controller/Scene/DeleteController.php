<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Scene\Delete\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class DeleteController
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    public function __construct(
        SceneResolver $sceneResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory,
        ApiResponseFactoryInterface $apiResponseFactory
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $chapterId = $scene->getChapter() ? $scene->getChapter()->getId(): null;
        $this->commandBus->dispatch(new Command($scene));

        if ('XMLHttpRequest' == $request->getHeader('X-Requested-With')) {
            return $this->apiResponseFactory->success();
        }

        return null !== $chapterId
            ? $this->responseFactory->redirectToRoute('chapter_edit', ['id' => $chapterId])
            : $this->responseFactory->redirectToRoute(
                'scene_list',
                ['page' => $request->getAttribute('page')]
            )
        ;
    }
}
