<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Ramsey\Uuid\UuidInterface;

final class ChaptersFilter
{
    /**
     * @var UuidInterface|null
     */
    private $selectedId;

    public function __construct(?UuidInterface $selectedId)
    {
        $this->selectedId = $selectedId;
    }

    public function getSelectedId(): ?UuidInterface
    {
        return $this->selectedId;
    }
}
