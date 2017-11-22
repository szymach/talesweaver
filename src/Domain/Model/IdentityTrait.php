<?php

declare(strict_types=1);

namespace Domain\Model;

use Ramsey\Uuid\UuidInterface;

trait IdentityTrait
{
    /**
     * @var UuidInterface
     */
    private $id;

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
