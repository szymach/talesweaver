<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Bus;

use RuntimeException;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Integration\Doctrine\Entity\User;

class AuthorAwareBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(MessageBus $messageBus, TokenStorageInterface $tokenStorage)
    {
        $this->messageBus = $messageBus;
        $this->tokenStorage = $tokenStorage;
    }

    public function handle($message): void
    {
        if (true === $message instanceof AuthorAwareInterface) {
            $user = $this->getUser();
            if (null === $user) {
                throw new RuntimeException(
                    sprintf('No user set when executing command "%s"', get_class($message))
                );
            }

            $message->setAuthor($user->getAuthor());
        }

        $this->messageBus->handle($message);
    }

    private function getUser(): ?User
    {
        return $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
    }
}
