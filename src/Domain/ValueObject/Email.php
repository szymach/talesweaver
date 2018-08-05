<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

use Assert\Assertion;

class Email
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        Assertion::email($value);
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
