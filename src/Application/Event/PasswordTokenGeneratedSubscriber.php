<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event;

use Talesweaver\Application\Bus\EventSubscriberInterface;
use Talesweaver\Application\Mailer\AuthorActionMailer;

final class PasswordTokenGeneratedSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorActionMailer
     */
    private $passwordResetMailer;

    public function __construct(AuthorActionMailer $passwordResetMailer)
    {
        $this->passwordResetMailer = $passwordResetMailer;
    }

    public function __invoke(PasswordTokenGenerated $event): void
    {
        $this->passwordResetMailer->send($event->getAuthor());
    }
}
