<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Event\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Event;

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

    public function __invoke(Event $event): ResponseInterface
    {
        $this->commandBus->dispatch(new Command($event));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
