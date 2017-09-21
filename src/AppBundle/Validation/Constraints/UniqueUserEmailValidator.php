<?php

namespace AppBundle\Validation\Constraints;

use AppBundle\Entity\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserEmailValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($email, Constraint $constraint)
    {
        if (!is_string($email) || $email === '' || strpos($email, '@') === false) {
            return;
        }

        if ($this->repository->findOneBy(['username' => $email])) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
