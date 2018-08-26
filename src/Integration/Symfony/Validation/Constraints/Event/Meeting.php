<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Validation\Constraints\Event;

use Symfony\Component\Validator\Constraint;

class Meeting extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
