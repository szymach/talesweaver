<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Talesweaver\Domain\ValueObject\Email;

class Author
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var string
     */
    private $password;

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

    public function __construct(UuidInterface $id, Email $email, string $password, string $activationToken)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $this->encodePassword($password);
        $this->activationTokens = new ArrayCollection([new ActivationToken($this, $activationToken)]);
        $this->passwordResetTokens = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $this->encodePassword($password);
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function activate(): void
    {
        if (true === $this->active) {
            throw new DomainException(sprintf('User "%s" is already active!', $this->id->toString()));
        }

        $this->active = true;
    }

    public function getActivationToken(): ?ActivationToken
    {
        $token = $this->activationTokens->filter(function (ActivationToken $code): bool {
            return $code->isValid();
        })->first();

        return (true === $token instanceof ActivationToken) ? $token : null;
    }

    public function addPasswordResetToken(string $token): void
    {
        $this->passwordResetTokens->add(new PasswordResetToken($this, $token));
    }

    public function getPasswordResetToken(): ?PasswordResetToken
    {
        $tokens = $this->passwordResetTokens->filter(function (PasswordResetToken $token): bool {
            return true === $token->isActive() && true === $token->isValid();
        });

        return false === $tokens->isEmpty() ? $tokens->first() : null;
    }

    private function encodePassword(string $password): string
    {
        $hashedPassword = password_hash($password, PASSWORD_ARGON2I);
        if (false === $hashedPassword) {
            throw new RuntimeException('Cannot encode password');
        }

        return $hashedPassword;
    }
}
