<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag;

class MessagesAwareBus implements CommandBus, MessageBusInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var FlashBag
     */
    private $flashBag;

    public function __construct(MessageBusInterface $messageBus, FlashBag $flashBag)
    {
        $this->messageBus = $messageBus;
        $this->flashBag = $flashBag;
    }

    public function dispatch($command): void
    {
        $this->messageBus->dispatch($command);
        if (false === $command instanceof MessageCommandInterface) {
            return;
        }

        $message = $command->getMessage();
        $this->flashBag->add(new Flash(
            $message->getType(),
            $message->getTranslationKey(),
            $message->getTranslationParameters()
        ));
    }
}
