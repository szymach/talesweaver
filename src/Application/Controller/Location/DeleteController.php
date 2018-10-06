<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Location\Delete\Command;
use Talesweaver\Application\Http\Entity\LocationResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class DeleteController
{
    /**
     * @var LocationResolver
     */
    private $locationResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        LocationResolver $locationResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->locationResolver = $locationResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(
            new Command($this->locationResolver->fromRequest($request))
        );

        return $this->responseFactory->toJson(['success' => true]);
    }
}
