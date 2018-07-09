<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Items
{
    public function find(UuidInterface $id): ?Item;
    public function findAll(): array;
    public function remove(UuidInterface $id): void;
}
