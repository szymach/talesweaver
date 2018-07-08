<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueUserEmail extends Constraint
{
    public $message = 'security.username_taken';
}
