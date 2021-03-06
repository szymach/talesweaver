<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

interface Positionable extends Authorable
{
    public function getPosition(): int;
    public function setPosition(int $position): void;
}
