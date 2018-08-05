<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Symfony\Component\Security\Core\User\UserInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Integration\Doctrine\Entity\ActivationToken;
use Talesweaver\Integration\Doctrine\Entity\PasswordResetToken;

class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var int
     */
    private $id;

    /**
     * @var Author
     */
    private $author;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var boolean
     */
    private $active = false;

    /**
     * @var ActivationToken[]|Collection
     */
    private $activationTokens;

    /**
     * @var PasswordResetToken[]|Collection
     */
    private $passwordResetTokens;

    /**
     * @param Author $author
     * @param string $password
     * @param string $activationToken
     */
    public function __construct(
        Author $author,
        string $password,
        string $activationToken
    ) {
        $this->author = $author;
        $this->password = $password;
        $this->roles = [self::ROLE_USER];
        $this->activationTokens = new ArrayCollection([new ActivationToken($this, $activationToken)]);
        $this->passwordResetTokens = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->author->getEmail();
    }

    public function getId(): int
    {
        return $this->id;
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
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function activate(): void
    {
        if (true === $this->active) {
            throw new DomainException(sprintf('User "%s" is already active!', $this->id));
        }

        $this->active = true;
    }

    public function getActivationToken(): ?ActivationToken
    {
        $codes = $this->activationTokens->filter(function (ActivationToken $code): bool {
            return $code->isValid();
        });

        return false === $codes->isEmpty() ? $codes->first(): null;
    }

    public function addPasswordResetToken(string $token): void
    {
        $this->passwordResetTokens->add(new PasswordResetToken($this, $token));
    }

    public function getPasswordResetToken(): ?PasswordResetToken
    {
        $tokens = $this->passwordResetTokens->filter(function (PasswordResetToken $token): bool {
            return $token->isValid();
        });

        return false === $tokens->isEmpty() ? $tokens->first() : null;
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
