<?php

namespace AppBundle\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueUserEmail extends Constraint
{
    public $message = 'security.username_taken';
}
