<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Location;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Application\Location\Delete\Command;
use Talesweaver\Domain\Location;

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
