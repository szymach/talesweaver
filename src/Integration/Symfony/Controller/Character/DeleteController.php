<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Character\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Character;

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

    public function __invoke(Character $character): ResponseInterface
    {
        $this->commandBus->dispatch(new Command($character));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
