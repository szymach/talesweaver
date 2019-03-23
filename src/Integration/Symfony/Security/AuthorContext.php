<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Security;

use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Talesweaver\Application\Security\AuthorContext as ApplicationAuthorContext;
use Talesweaver\Domain\Author;

final class AuthorContext implements ApplicationAuthorContext
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getAuthor(): Author
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            throw new RuntimeException('No user logged in.');
        }

        $user = $token->getUser();
        if (false === $user instanceof User) {
            throw new RuntimeException(sprintf(
                'Expected instance of "%s", got "%s"',
                User::class,
                true === is_object($user) ? get_class($user) : gettype($user)
            ));
        }

        return $user->getAuthor();
    }

    public function logout(): void
    {
        $this->tokenStorage->setToken(null);
    }
}
