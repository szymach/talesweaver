<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Query\Security\AuthorByEmail;
use Talesweaver\Domain\ValueObject\Email;

class UniqueUserEmailValidator extends ConstraintValidator
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function validate($email, Constraint $constraint)
    {
        if (false === is_string($email) || '' === $email || false === strpos($email, '@')) {
            return;
        }

        if (null !== $this->queryBus->query(new AuthorByEmail(new Email($email)))) {
            $this->context->buildViolation('security.email_taken')->addViolation();
        }
    }
}
