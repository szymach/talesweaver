<?php

namespace App\Controller\Item;

use Domain\Item\Delete\Command;
use App\Entity\Item;
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
        $this->commandBus->handle(new Command($item));

        return new JsonResponse(['success' => true]);
    }
}
