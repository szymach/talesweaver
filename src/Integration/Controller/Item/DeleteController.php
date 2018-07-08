<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Item;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Application\Item\Delete\Command;
use Talesweaver\Domain\Item;

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
