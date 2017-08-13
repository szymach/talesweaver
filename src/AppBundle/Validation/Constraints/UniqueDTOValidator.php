<?php

namespace AppBundle\Validation\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueDTOValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(
        EntityManagerInterface $manager,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->manager = $manager;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function validate($dto, Constraint $constraint)
    {
        if (!is_object($dto)) {
            return;
        }

        $criteria = [];
        foreach ($constraint->fields as $field => $dqlField) {
            $criteria[$dqlField] = $this->propertyAccessor->getValue($dto, $field);
        }

        $id = null;
        if ($constraint->id) {
            $id = $this->propertyAccessor->getValue($dto, $constraint->id);
        }
        if ($this->manager->getRepository($constraint->entityClass)->{$constraint->repositoryMethod}($criteria, $id)) {
            $violation = $this->context->buildViolation($constraint->message);
            if ($constraint->path) {
                $violation->atPath($constraint->path);
            }
            $violation->addViolation();
        }
    }
}
