<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

interface Authorable
{
    public function getCreatedBy(): Author;
}
