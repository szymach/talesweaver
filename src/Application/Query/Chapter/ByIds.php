<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;

final class ByIds
{
    /**
     * @var UuidInterface[]
     */
    private $ids;

    public function __construct(array $ids)
    {
        Assertion::allIsInstanceOf($ids, UuidInterface::class);
        $this->ids = $ids;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
