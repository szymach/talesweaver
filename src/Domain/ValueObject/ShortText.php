<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

use Assert\Assertion;

final class ShortText
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        Assertion::minLength($value, 1, 'The short text needs at least 1 character.');
        Assertion::maxLength(
            $value,
            255,
            sprintf('The text can be only 255 characters long, but is "%s"', mb_strlen($value))
        );
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
