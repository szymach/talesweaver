<?php

declare(strict_types=1);

namespace Integration\Bus;

use Application\Messages\MessageCommandInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class MessagesAwareBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        MessageBus $messageBus,
        Session $session,
        TranslatorInterface $translator
    ) {
        $this->messageBus = $messageBus;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function handle($command): void
    {
        $this->messageBus->handle($command);
        if (false === $command instanceof MessageCommandInterface) {
            return;
        }

        $message = $command->getMessage();
        $this->session->getFlashBag()->set(
            $message->getType(),
            $this->translator->trans($message->getTranslationKey(), $message->getTranslationParameters())
        );
    }
}
