<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Bus;

use RuntimeException;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Integration\Doctrine\Entity\User;

class AuthorAccessBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    public function __construct(MessageBus $messageBus, TokenStorageInterface $tokenStorage)
    {
        $this->messageBus = $messageBus;
        $this->tokenStorage = $tokenStorage;
    }

    public function handle($message): void
    {
        if (true === $message instanceof AuthorAccessInterface) {
            $user = $this->getUser();
            if (null === $user) {
                throw new RuntimeException(
                    sprintf('No user set when executing command "%s"', get_class($message))
                );
            }

            if (false === $message->isAllowed($user->getAuthor())) {
                throw new AccessDeniedException(sprintf(
                    'Access denied to command "%s" for user "%s"',
                    get_class($message),
                    $user->getId()
                ));
            }
        }

        $this->messageBus->handle($message);
    }

    private function getUser(): ?User
    {
        return $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
    }
}
