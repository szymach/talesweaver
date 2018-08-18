<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Scenes
{
    public function find(UuidInterface $id): ?Scene;
    public function add(Scene $scene): void;
    public function remove(UuidInterface $id): void;
}
