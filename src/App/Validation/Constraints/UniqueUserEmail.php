<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueUserEmail extends Constraint
{
    public $message = 'security.username_taken';
}
