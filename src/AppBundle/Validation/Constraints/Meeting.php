<?php

namespace AppBundle\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

class Meeting extends Constraint
{
    public $message = 'event.meeting.cannot_meet_itself';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
