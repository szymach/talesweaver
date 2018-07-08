<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Talesweaver\Domain\Event\Meeting;

class MeetingValidator extends ConstraintValidator
{
    /**
     * @param Meeting $meeting
     */
    public function validate($meeting, Constraint $constraint)
    {
        if (null === $meeting->getRoot() || null === $meeting->getRelation()) {
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
