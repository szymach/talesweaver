<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Traits;

trait PositionableTrait
{
    /**
     * @var int
     */
    private $position;

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}
