<?php

declare(strict_types=1);

namespace AppBundle\Mail;

use AppBundle\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class RegistrationMailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
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
        EngineInterface $templating,
        TranslatorInterface $translator,
        string $mailerFrom
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->mailerFrom = $mailerFrom;
    }

    public function send(User $user): int
    {
        $message = new Swift_Message(
            $this->translator->trans('security.registration.mail.title')
        );
        $message->setFrom($this->mailerFrom);
        $message->setTo($user->getUsername());
        $message->setBody($this->templating->render(
            'security/mail/registration.html.twig',
            ['code' => $user->getActivationCode()]
        ), 'text/html');

        return $this->mailer->send($message);
    }
}
