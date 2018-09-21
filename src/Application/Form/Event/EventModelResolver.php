<?php

declare(strict_types=1);

namespace Talesweaver\Application\Form\Event;

interface EventModelResolver
{
    public function resolve(string $model): string;
}
