<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Session;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag as ApplicationFlashBag;

class FlashBag implements ApplicationFlashBag
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FlashBagInterface|null
     */
    private $flashBag;

    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    public function add(Flash $flash): void
    {
        $message = $this->translator->trans($flash->getKey(), $flash->getParameters());
        if (true === $this->hasEqualMessage($flash->getType(), $message)) {
            return;
        }

        $this->getFlashBag()->add($flash->getType(), $message);
    }

    private function hasEqualMessage(string $type, string $message): bool
    {
        $equals = array_filter(
            $this->getFlashBag()->peek($type),
            function (string $addedMessage) use ($message): bool {
                return $message === $addedMessage;
            }
        );

        return 0 !== count($equals);
    }

    private function getFlashBag(): FlashBagInterface
    {
        if (null === $this->flashBag) {
            $this->flashBag = $this->session->getFlashBag();
        }

        return $this->flashBag;
    }
}
