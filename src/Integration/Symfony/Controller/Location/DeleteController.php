<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Command\Location\Delete\Command;
use Talesweaver\Domain\Location;

class DeleteController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(CommandBus $commandBus, ResponseFactoryInterface $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Location $location): ResponseInterface
    {
        $this->commandBus->dispatch(new Command($location));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
