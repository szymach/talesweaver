<?php

declare(strict_types=1);

namespace AppBundle\Bus;

use AppBundle\Bus\Messages\MessageCommandInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class MessagesAwareBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

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
        $this->flashBag = $session->getFlashBag();
        $this->translator = $translator;
    }

    public function handle($command): void
    {
        if ($command instanceof MessageCommandInterface && $command->hasMessage()) {
            $message = $command->getMessage();
            $this->flashBag->add(
                $message->getType(),
                $this->translator->trans(
                    $message->getTranslationKey(),
                    $message->getTranslationParameters()
                )
            );
        }

        $this->messageBus->handle($command);
    }
}
