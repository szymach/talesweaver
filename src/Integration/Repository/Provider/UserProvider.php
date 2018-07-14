<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository\Provider;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Domain\Author;
use Talesweaver\Integration\Doctrine\Entity\User;

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

    public function fetchCurrentUsersAuthor(): Author
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token || null === $token->getUser()) {
            throw new AccessDeniedException('No currently logged in user!');
        }

        /* @var $user User */
        $user = $token->getUser();
        return $user->getAuthor();
    }
}
