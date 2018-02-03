<?php

declare(strict_types=1);

namespace App\Controller\Event;

use Domain\Entity\Event;
use Domain\Event\Delete\Command;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function __invoke(Event $event)
    {
        $this->commandBus->handle(new Command($event));

        return new JsonResponse(['success' => true]);
    }
}
