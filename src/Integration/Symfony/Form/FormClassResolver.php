<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form;

use Assert\Assertion;
use InvalidArgumentException;
use Talesweaver\Application\Form\Form;

class FormClassResolver
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
            throw new InvalidArgumentException(sprintf(
                '"%s" does not have a corresponding Symfony form.',
                $identity
            ));
        }

        return get_class($form);
    }
}
