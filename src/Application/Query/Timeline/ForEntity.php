<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Timeline;

use Ramsey\Uuid\UuidInterface;

class ForEntity
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $class;

    public function __construct(UuidInterface $id, string $class)
    {
        $this->id = $id;
        $this->class = $class;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
