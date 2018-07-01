<?php

declare(strict_types=1);

namespace Domain\Traits;

use DateTimeImmutable;
use DateTimeInterface;

trait TimestampableTrait
{
    /**
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @var DateTimeInterface
     */
    private $updatedAt;

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    private function update(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
