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
     * @var string
     */
    private $field;

    /**
     * @var bool
     */
    private $sortable;

    public function __construct(string $label, string $field, bool $sortable)
    {
        $this->label = $label;
        $this->field = $field;
        $this->sortable = $sortable;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }
}
