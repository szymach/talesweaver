<?php

declare(strict_types=1);

namespace Talesweaver\Application\Data;

use Ramsey\Uuid\UuidInterface;

final class Filter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $options;

    /**
     * @var UuidInterface|null
     */
    private $selected;

    public function __construct(string $name, array $options, ?UuidInterface $selected)
    {
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getSelected(): ?UuidInterface
    {
        return $this->selected;
    }
}
