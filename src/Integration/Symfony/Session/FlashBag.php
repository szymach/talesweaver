<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Session;

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

    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    public function add(Flash $flash): void
    {
        $this->session->getFlashBag()->add(
            $flash->getType(),
            $this->translator->trans($flash->getKey(), $flash->getParameters())
        );
    }
}
