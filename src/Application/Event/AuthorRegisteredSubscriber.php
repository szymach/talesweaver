<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event;

use Talesweaver\Application\Bus\EventSubscriberInterface;
use Talesweaver\Application\Mailer\AuthorActionMailer;

final class AuthorRegisteredSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorActionMailer
     */
    private $newAuthorMailer;

    public function __construct(AuthorActionMailer $newAuthorMailer)
    {
        $this->newAuthorMailer = $newAuthorMailer;
    }

    public function __invoke(AuthorRegistered $event): void
    {
        $this->newAuthorMailer->send($event->getAuthor());
    }
}
