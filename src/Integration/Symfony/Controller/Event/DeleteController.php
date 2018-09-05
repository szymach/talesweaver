<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Event\Delete\Command;
use Talesweaver\Domain\Event;

class DeleteController
{
    /**
     * @var MessageBus
     */
    private $commandBus;

    public function __construct(MessageBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Event $event): ResponseInterface
    {
        $this->commandBus->handle(new Command($event));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
