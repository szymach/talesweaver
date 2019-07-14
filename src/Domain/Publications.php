<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Publications
{
    public function find(UuidInterface $id): ?Publication;
    public function findPublic(UuidInterface $id): ?Publication;
    public function remove(UuidInterface $id): void;
}
