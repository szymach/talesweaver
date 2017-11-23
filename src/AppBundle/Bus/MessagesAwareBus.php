<?php

declare(strict_types=1);

namespace AppBundle\Bus;

use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class MessagesAwareBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        MessageBus $messageBus,
        RequestStack $requestStack,
        TranslatorInterface $translator
    ) {
        $this->messageBus = $messageBus;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function handle($command): void
    {
        $this->messageBus->handle($command);
        if ($command instanceof MessageCommandInterface) {
            $this->setFlash($command->getMessage());
        }
    }

    private function setFlash(Message $message): void
    {
        /* @var $session Session */
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $session->getFlashBag()->set(
            $message->getType(),
            $this->translator->trans(
                $message->getTranslationKey(),
                $message->getTranslationParameters()
            )
        );
    }
}
