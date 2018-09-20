<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Talesweaver\Application\Bus\QueryBus as ApplicationQueryBus;

class QueryBus implements ApplicationQueryBus
{
    /**
     * @var MessageBusInterface
     */
    private $messenger;

    public function __construct(MessageBusInterface $messenger)
    {
        $this->messenger = $messenger;
    }

    public function query(object $command)
    {
        return $this->messenger->dispatch($command);
    }
}
