<?php

declare(strict_types=1);

namespace Domain\Entity;

use Domain\Entity\User\ActivationToken;
use Domain\Entity\User\PasswordResetToken;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

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
     * @param string $username
     * @param string $password
     * @param string $activationToken
     */
    public function __construct(
        string $username,
        string $password,
        string $activationToken
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->roles = [self::ROLE_USER];
        $this->activationTokens = new ArrayCollection([new ActivationToken($this, $activationToken)]);
        $this->passwordResetTokens = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
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
        if ($this->active) {
            throw new DomainException(sprintf('User "%s" is already active!', $this->id));
        }

        $this->active = true;
    }

    public function getActivationToken(): ?ActivationToken
    {
        $codes = $this->activationTokens->filter(function (ActivationToken $code) {
            return $code->isValid();
        });

        return $codes->count() > 0 ? $codes->first(): null;
    }

    public function addPasswordResetToken(string $token): void
    {
        $this->passwordResetTokens->add(new PasswordResetToken($this, $token));
    }

    public function getPasswordResetToken(): ?PasswordResetToken
    {
        $tokens = $this->passwordResetTokens->filter(function (PasswordResetToken $token) {
            return $token->isValid();
        });

        return $tokens->count() > 0 ? $tokens->first() : null;
    }

    public function getRoles(): array
    {
        if (is_string($this->roles)) {
            $this->roles = json_decode($this->roles);
        }

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
