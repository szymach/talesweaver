<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Security\AuthorAwareInterface;

class AuthorAwareBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(MessageBus $messageBus, AuthorContext $authorContext)
    {
        $this->messageBus = $messageBus;
        $this->authorContext = $authorContext;
    }

    public function handle($message): void
    {
        if (true === $message instanceof AuthorAwareInterface) {
            $message->setAuthor($this->authorContext->getAuthor());
        }

        $this->messageBus->handle($message);
    }
}
