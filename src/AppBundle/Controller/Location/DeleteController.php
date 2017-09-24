<?php

namespace AppBundle\Controller\Location;

use AppBundle\Location\Delete\Command;
use AppBundle\Entity\Location;
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
