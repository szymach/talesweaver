<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Character\Delete\Command;
use Talesweaver\Application\Http\Entity\CharacterResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class DeleteController
{
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
        CharacterResolver $characterResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->characterResolver = $characterResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(
            new Command($this->characterResolver->fromRequest($request))
        );

        return $this->responseFactory->toJson(['success' => true]);
    }
}
