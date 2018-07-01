<?php

declare(strict_types=1);

namespace Domain\Entity\User;

use DateInterval;
use DateTimeImmutable;
use Domain\Entity\User;

class PasswordResetToken
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var boolean
     */
    private $active = true;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user, string $value)
    {
        $this->value = $value;
        $this->user = $user;
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return $this->value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isValid(): bool
    {
        /* @var $interval DateInterval */
        $interval = (new DateTimeImmutable())->diff($this->createdAt);
        return $interval->days <= 1 && $interval->h <= 24;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function deactivate(): void
    {
        $this->active = false;
    }
}
