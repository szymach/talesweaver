<?php

namespace AppBundle\Entity\Traits;

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

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt() : ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    private function update()
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
