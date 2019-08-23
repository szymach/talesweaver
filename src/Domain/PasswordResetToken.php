<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DateInterval;
use DateTimeImmutable;

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
     * @var Author
     */
    private $author;

    public function __construct(Author $author, string $value)
    {
        $this->value = $value;
        $this->author = $author;
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

    public function getValidUntil(): DateTimeImmutable
    {
        return $this->createdAt->add(new DateInterval('P1D'));
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function isValid(): bool
    {
        /** @var DateInterval $interval */
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
