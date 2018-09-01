<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag;

class MessagesAwareBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var FlashBag
     */
    private $flashBag;

    public function __construct(MessageBus $messageBus, FlashBag $flashBag)
    {
        $this->messageBus = $messageBus;
        $this->flashBag = $flashBag;
    }

    public function handle($command): void
    {
        $this->messageBus->handle($command);
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
