<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Positionable\IncreaseSingle\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;

final class IncreasePositionController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    public function __construct(
        ChapterResolver $chapterResolver,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $apiResponseFactory
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->commandBus = $commandBus;
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $this->commandBus->dispatch(new Command($chapter));

        return $this->apiResponseFactory->success();
    }
}
