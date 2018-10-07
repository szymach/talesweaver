<?php

declare(strict_types=1);

namespace Talesweaver\Application\Bus;

interface EventBus
{
    public function send(object $event): void;
}
