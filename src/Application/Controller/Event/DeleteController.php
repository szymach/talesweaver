<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Event\Delete\Command;
use Talesweaver\Application\Http\Entity\EventResolver;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;

class DeleteController
{
    /**
     * @var EventResolver
     */
    private $eventResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        EventResolver $eventResolver,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $responseFactory
    ) {
        $this->eventResolver = $eventResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(
            new Command($this->eventResolver->fromRequest($request))
        );

        return $this->responseFactory->success();
    }
}
