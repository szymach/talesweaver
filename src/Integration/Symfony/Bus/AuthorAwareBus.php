<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Security\AuthorAwareInterface;

class AuthorAwareBus implements CommandBus, MessageBusInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(MessageBusInterface $messageBus, AuthorContext $authorContext)
    {
        $this->messageBus = $messageBus;
        $this->authorContext = $authorContext;
    }

    public function dispatch($message): void
    {
        if (true === $message instanceof AuthorAwareInterface) {
            $message->setAuthor($this->authorContext->getAuthor());
        }

        $this->messageBus->dispatch($message);
    }
}
