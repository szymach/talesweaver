<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Characters
{
    public function find(UuidInterface $id): ?Character;
    public function findForScene(Scene $scene): array;
    public function findRelated(Scene $scene): array;
    public function findForEvent(Scene $scene): array;
    public function findNamesForScene(Scene $scene): array;
    public function add(Character $character): void;
    public function remove(UuidInterface $id): void;
    public function entityExists(string $name, ?UuidInterface $id, ?Scene $scene): bool;
}
