<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Characters
{
    public function find(UuidInterface $id): ?Character;
    public function findAll(): array;
    public function add(Character $character): void;
    public function remove(UuidInterface $id): void;
}
