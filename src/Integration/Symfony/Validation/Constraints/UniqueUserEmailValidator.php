<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Doctrine\Repository\AuthorRepository;

class UniqueUserEmailValidator extends ConstraintValidator
{
    /**
     * @var AuthorRepository
     */
    private $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($email, Constraint $constraint)
    {
        if (false === is_string($email) || '' === $email || false === strpos($email, '@')) {
            return;
        }

        if (null !== $this->repository->findOneByEmail(new Email($email))) {
            $this->context->buildViolation('security.email_taken')->addViolation();
        }
    }
}
