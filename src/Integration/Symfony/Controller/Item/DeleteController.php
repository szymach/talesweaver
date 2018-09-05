<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use SimpleBus\Message\Bus\MessageBus;
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

    public function __invoke(Item $item): ResponseInterface
    {
        $this->commandBus->handle(new Command($item));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
