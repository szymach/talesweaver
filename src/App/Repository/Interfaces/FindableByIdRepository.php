<?php

declare(strict_types=1);

namespace App\Repository\Interfaces;

interface FindableByIdRepository
{
    public function getClassName(): string;

    public function find(string $id);
}
