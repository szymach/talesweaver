<?php

namespace AppBundle\Traits;

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
