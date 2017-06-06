<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Event;
use AppBundle\Event\Delete\Command;
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
        $this->commandBus->handle(new Command($event->getId()));

        return new JsonResponse(['success' => true]);
    }
}
