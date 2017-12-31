<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use Domain\Event\Meeting;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MeetingValidator extends ConstraintValidator
{
    /**
     * @param Meeting $meeting
     */
    public function validate($meeting, Constraint $constraint)
    {
        if (!$meeting->getRoot() || !$meeting->getRelation()) {
            return;
        }

        if ($meeting->getRoot() === $meeting->getRelation()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('relation')
                ->addViolation()
            ;
        }
    }
}
