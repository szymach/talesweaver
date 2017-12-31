<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserProvider
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function fetchCurrentUser(): User
    {
        $token = $this->tokenStorage->getToken();
        if (!$token || !$token->getUser()) {
            throw new AccessDeniedException('No currently logged in user!');
        }

        return $token->getUser();
    }
}
