<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Talesweaver\Domain\ValueObject\Email;

interface Administrators
{
    public function add(Administrator $administrator): void;
    public function remove(Administrator $administrator): void;
    public function findByEmail(Email $email): ?Administrator;
}
