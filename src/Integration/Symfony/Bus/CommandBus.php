<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Talesweaver\Application\Bus\CommandBus as ApplicationCommandBus;

class CommandBus implements ApplicationCommandBus
{
    /**
     * @var MessageBusInterface
     */
    private $messenger;

    public function __construct(MessageBusInterface $messenger)
    {
        $this->messenger = $messenger;
    }

    public function dispatch(object $command): void
    {
        $this->messenger->dispatch($command);
    }
}
