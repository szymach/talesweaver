<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security;

interface AuthenticationContext
{
    public function lastProvidedUsername(): ?string;
    public function lastError(): ?string;
}
