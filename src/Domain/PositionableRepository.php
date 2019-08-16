<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

interface PositionableRepository
{
    public function increasePosition(Positionable $item): void;
    public function decreasePosition(Positionable $item): void;
    public function supportsPositionable(Positionable $item): bool;
}
