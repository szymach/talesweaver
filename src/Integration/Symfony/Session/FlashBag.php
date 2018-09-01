<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Session;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag as ApplicationFlashBag;

class FlashBag implements ApplicationFlashBag
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public function add(Flash $flash)
    {
        $this->flashBag->add(
            $flash->getType(),
            $this->translator->trans($flash->getKey(), $flash->getParameters())
        );
    }
}
