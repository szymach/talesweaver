<?php

declare(strict_types=1);

namespace Talesweaver\Application\Data;

final class Header
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var bool
     */
    private $sortable;

    public function __construct(string $label, bool $sortable)
    {
        $this->label = $label;
        $this->sortable = $sortable;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }
}
