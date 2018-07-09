<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Chapters
{
    public function find(UuidInterface $id): ?Chapter;
    public function findAll(): array;
    public function remove(UuidInterface $id): void;
}
