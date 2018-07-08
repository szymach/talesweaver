<?php

declare(strict_types=1);

namespace Integration\Controller\Location;

use Domain\Location;
use Application\Location\Delete\Command;
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

    public function __invoke(Location $location)
    {
        $this->commandBus->handle(new Command($location));

        return new JsonResponse(['success' => true]);
    }
}
