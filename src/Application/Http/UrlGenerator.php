<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http;

interface UrlGenerator
{
    public function generate(string $route, ?array $parameters = []): string;
}
