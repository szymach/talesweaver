<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository\Interfaces;

interface FindableByIdRepository
{
    public function getClassName(): string;

    public function find(string $id);
}
