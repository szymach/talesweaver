<?php

namespace AppBundle\Location\Delete;

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

    public function getId() : UuidInterface
    {
        return $this->id;
    }
}
