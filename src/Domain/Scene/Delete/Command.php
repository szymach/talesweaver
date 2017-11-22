<?php

declare(strict_types=1);

namespace Domain\Scene\Delete;

use Ramsey\Uuid\UuidInterface;

class Command
{
    /**
     * @var UuidInterface
     */
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
