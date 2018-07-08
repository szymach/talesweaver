<?php

declare(strict_types=1);

namespace Integration\Repository\Interfaces;

interface FindableByIdRepository
{
    public function getClassName(): string;

    public function find(string $id);
}
