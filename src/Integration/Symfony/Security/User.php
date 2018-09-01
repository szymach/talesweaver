<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Talesweaver\Domain\Author;

class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var Author
     */
    private $author;

    /**
     * @var array
     */
    private $roles;

    public function __construct(Author $author)
    {
        $this->author = $author;
        $this->roles = [self::ROLE_USER];
    }

    public function __toString()
    {
        return (string) $this->author->getEmail();
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getUsername(): string
    {
        return (string) $this->author->getEmail();
    }

    public function getPassword(): string
    {
        return $this->author->getPassword();
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
