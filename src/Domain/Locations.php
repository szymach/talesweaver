<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Locations
{
    public function find(UuidInterface $id): ?Location;
    public function findAll(): array;
    public function remove(UuidInterface $id): void;
}
