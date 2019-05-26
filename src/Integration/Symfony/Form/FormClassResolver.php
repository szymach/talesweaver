<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form;

use Assert\Assertion;
use InvalidArgumentException;
use Talesweaver\Application\Form\Form;

final class FormClassResolver
{
    /**
     * @var Form[]
     */
    private $forms;

    public function __construct(array $forms)
    {
        Assertion::allIsInstanceOf($forms, Form::class);
        $this->forms = $forms;
    }

    /**
     * @param string $identity - form interface from the application layer
     * @return string - form class for Symfony's Form component
     * @throws InvalidArgumentException
     */
    public function resolve(string $identity): string
    {
        $form = array_reduce(
            $this->forms,
            function (?Form $accumulator, Form $form) use ($identity): ?Form {
                return true === $form instanceof $identity ? $form : $accumulator;
            },
            null
        );

        if (null === $form) {
            throw new InvalidArgumentException("\"{$identity}\" does not have a corresponding Symfony form.");
        }

        return get_class($form);
    }
}
