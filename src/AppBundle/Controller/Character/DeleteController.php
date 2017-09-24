<?php

namespace AppBundle\Controller\Character;

use AppBundle\Character\Delete\Command;
use AppBundle\Entity\Character;
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

    public function __invoke(Character $character)
    {
        $this->commandBus->handle(new Command($character));

        return new JsonResponse(['success' => true]);
    }
}
