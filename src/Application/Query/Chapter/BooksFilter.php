<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Ramsey\Uuid\UuidInterface;

final class BooksFilter
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
