<?php

declare(strict_types=1);

namespace App\Bus;

use App\Entity\User;
use Domain\Security\UserAwareInterface;
use RuntimeException;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserAwareBus implements MessageBus
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
        if ($message instanceof UserAwareInterface) {
            $user = $this->getUser();
            if (!$user) {
                $this->throwNoUserException(get_class($message));
            }

            $message->setUser($user);
        }

        $this->messageBus->handle($message);
    }

    private function getUser(): ?User
    {
        return $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null
        ;
    }

    /**
     * @param string $class
     * @throws RuntimeException
     */
    private function throwNoUserException(string $class): void
    {
        throw new RuntimeException(sprintf('No user set when executing command %s', $class));
    }
}
