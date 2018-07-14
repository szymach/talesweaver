<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository\Interfaces;

interface RequestSecuredRepository
{
    public function getClassName(): string;
}
