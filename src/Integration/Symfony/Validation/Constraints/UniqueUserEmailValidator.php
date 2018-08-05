<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Doctrine\Repository\UserRepository;

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
        if (false === is_string($email) || '' === $email || false === strpos($email, '@')) {
            return;
        }

        if (null !== $this->repository->findOneByEmail(new Email($email))) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
