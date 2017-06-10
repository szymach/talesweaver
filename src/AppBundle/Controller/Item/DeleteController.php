<?php

namespace AppBundle\Controller\Item;

use AppBundle\Item\Delete\Command;
use AppBundle\Entity\Item;
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

    public function __invoke(Item $item)
    {
        $this->commandBus->handle(new Command($item->getId()));

        return new JsonResponse(['success' => true]);
    }
}
