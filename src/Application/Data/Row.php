<?php

declare(strict_types=1);

namespace Talesweaver\Application\Data;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;

final class Row
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var Cell[]
     */
    private $columns;

    public function __construct(UuidInterface $id, array $columns)
    {
        Assertion::allIsInstanceOf($columns, Cell::class);
        $this->id = $id;
        $this->columns = $columns;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}
