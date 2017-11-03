<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User\ActivationToken;
use AppBundle\Entity\User\PasswordResetToken;
use AppBundle\Security\TokenGenerator;
use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
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
     * @var UserRole[]|Collection
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
     * @param UserRole[] $roles
     * @param TokenGenerator $generator
     */
    public function __construct(
        string $username,
        string $password,
        array $roles,
        TokenGenerator $generator
    ) {
        Assert::thatAll($roles)->isInstanceOf(UserRole::class);

        $this->username = $username;
        $this->password = $password;
        $this->roles = new ArrayCollection($roles);
        $this->activationTokens = new ArrayCollection(
            [$generator->generateUserActivationToken($this)]
        );
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

        return $codes->count() > 0 ? $codes->first() : null;
    }

    public function addPasswordResetToken(TokenGenerator $generator): void
    {
        $this->passwordResetTokens->add($generator->generatePasswordActivationToken($this));
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
        return $this->roles->toArray();
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
