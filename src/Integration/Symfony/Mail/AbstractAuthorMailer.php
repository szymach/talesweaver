<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Mail;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Translation\TranslatorInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Mailer\AuthorActionMailer;
use Talesweaver\Domain\Author;

abstract class AbstractAuthorMailer implements AuthorActionMailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

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
        HtmlContent $htmlContent,
        TranslatorInterface $translator,
        string $mailerFrom
    ) {
        $this->mailer = $mailer;
        $this->htmlContent = $htmlContent;
        $this->translator = $translator;
        $this->mailerFrom = $mailerFrom;
    }

    protected function doSend(
        Author $author,
        string $title,
        string $template,
        array $templateParameters = []
    ): bool {
        $message = new Swift_Message($this->translator->trans($title));
        $message->setFrom($this->mailerFrom);
        $message->setTo((string) $author->getEmail());
        $message->setBody(
            $this->htmlContent->fromTemplate($template, $templateParameters),
            'text/html'
        );

        return 0 !== $this->mailer->send($message);
    }
}
