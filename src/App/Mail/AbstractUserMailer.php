<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use App\Templating\Engine;
use Symfony\Component\Translation\TranslatorInterface;

class AbstractUserMailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $mailerFrom;

    public function __construct(
        Swift_Mailer $mailer,
        Engine $templating,
        TranslatorInterface $translator,
        string $mailerFrom
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->mailerFrom = $mailerFrom;
    }

    protected function doSend(
        User $user,
        string $title,
        string $template,
        array $templateParameters = []
    ): int {
        $message = new Swift_Message($this->translator->trans($title));
        $message->setFrom($this->mailerFrom);
        $message->setTo($user->getUsername());
        $message->setBody(
            $this->templating->render($template, $templateParameters),
            'text/html'
        );

        return $this->mailer->send($message);
    }
}