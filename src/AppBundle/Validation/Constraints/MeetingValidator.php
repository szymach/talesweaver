<?php

namespace AppBundle\Validation\Constraints;

use AppBundle\Model\Meeting;
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
