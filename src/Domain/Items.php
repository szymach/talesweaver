<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Items
{
    public function find(UuidInterface $id): ?Item;
    public function findForScene(Scene $scene): array;
    public function findRelated(Scene $scene): array;
    public function findForEvent(Scene $scene): array;
    public function findNamesForScene(Scene $scene): array;
    public function add(Item $item): void;
    public function remove(UuidInterface $id): void;
    public function entityExists(string $name, ?UuidInterface $id, ?Scene $scene): bool;
}
