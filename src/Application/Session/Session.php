<?php

declare(strict_types=1);

namespace Talesweaver\Application\Session;

interface Session
{
    public function has(string $key): bool;
    public function set(string $key, $value): void;
    public function get(string $key, $default = null);
    public function remove(string $key): void;
}
