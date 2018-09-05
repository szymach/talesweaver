<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use SimpleBus\Message\Bus\MessageBus;
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

    public function __invoke(Location $location): ResponseInterface
    {
        $this->commandBus->handle(new Command($location));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
