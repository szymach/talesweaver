<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

use Assert\Assertion;

class LongText
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        Assertion::minLength($value, 1, 'The long text needs at least 1 character.');
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
