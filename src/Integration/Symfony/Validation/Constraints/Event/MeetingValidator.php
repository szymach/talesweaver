<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Validation\Constraints\Event;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MeetingValidator extends ConstraintValidator
{
    public function validate($meeting, Constraint $constraint)
    {
        if (null === $meeting->getRoot() || null === $meeting->getRelation()) {
            return;
        }

        if ($meeting->getRoot() === $meeting->getRelation()) {
            $this->context->buildViolation('event.meeting.cannot_meet_itself')
                ->atPath('relation')
                ->addViolation()
            ;
        }
    }
}
