<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http;

interface HtmlContent
{
    public function fromTemplate(string $template, array $parameters): string;
}
