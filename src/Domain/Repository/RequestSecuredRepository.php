<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Repository;

use Ramsey\Uuid\UuidInterface;

interface RequestSecuredRepository
{
    public function find(UuidInterface $id);
    public function getClassName(): string;
}
