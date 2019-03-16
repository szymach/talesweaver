<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
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

    public function query(object $query)
    {
        $envelope = $this->messenger->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (null === $handledStamp || false === $handledStamp instanceof HandledStamp) {
            return null;
        }

        return $handledStamp->getResult();
    }
}
