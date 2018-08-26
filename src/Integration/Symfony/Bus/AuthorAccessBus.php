<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

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
        if (true === $message instanceof AuthorAccessInterface) {
            $user = $this->getUser();
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

    private function getUser(): User
    {
        if (null === $this->tokenStorage->getToken()
            || false === is_object($this->tokenStorage->getToken()->getUser())
        ) {
            throw new RuntimeException('No logged in user');
        }

        $user = $this->tokenStorage->getToken()->getUser();
        if (false === $user instanceof User) {
            throw new RuntimeException(sprintf(
                '"%s" is not instance of "%s"',
                true === is_object($user) ? get_class($user) : gettype($user),
                User::class
            ));
        }

        return $user;
    }
}
