<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DomainException;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Talesweaver\Domain\ValueObject\Email;
use function password_hash;

final class Administrator
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
    private $active;

    public function __construct(UuidInterface $id, Email $email, string $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $this->encodePassword($password);
        $this->active = false;
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
            throw new DomainException("'User \"{$this->id->toString()}\" is already active!'");
        }

        $this->active = true;
    }

    public function deactivate(): void
    {
        if (false === $this->active) {
            throw new DomainException("'User \"{$this->id->toString()}\" is already inactive!'");
        }

        $this->active = false;
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
