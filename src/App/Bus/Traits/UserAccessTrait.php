<?php

declare(strict_types=1);

namespace App\Bus\Traits;

use App\Entity\User;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait UserAccessTrait
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @return User|null
     */
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