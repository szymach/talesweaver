<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\User;
use DateInterval;
use DateTimeImmutable;

class ActivationToken
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
     * @var User
     */
    private $user;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

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

    public function isValid(): bool
    {
        /* @var $interval DateInterval */
        $interval = (new DateTimeImmutable())->diff($this->createdAt);
        return $interval->days <= 1 && $interval->h <= 24;
    }
}
