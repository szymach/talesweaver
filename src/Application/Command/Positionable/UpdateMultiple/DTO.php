<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Positionable\UpdateMultiple;

use Talesweaver\Domain\Positionable;

final class DTO
{
    /**
     * @var Positionable
     */
    private $positionable;

    /**
     * @var int
     */
    private $position;

    public function __construct(Positionable $positionable, int $position)
    {
        $this->positionable = $positionable;
        $this->position = $position;
    }

    public function getPositionable(): Positionable
    {
        return $this->positionable;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
