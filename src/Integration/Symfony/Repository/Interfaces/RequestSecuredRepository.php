<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository\Interfaces;

use Ramsey\Uuid\UuidInterface;

interface RequestSecuredRepository
{
    public function find(UuidInterface $id);
    public function getClassName(): string;
}
