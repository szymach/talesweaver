<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Character;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Application\Character\Delete\Command;
use Talesweaver\Domain\Character;

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
