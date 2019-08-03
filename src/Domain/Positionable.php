<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

interface Positionable
{
    public function getPosition(): int;
    public function setPosition(int $position): void;
}
