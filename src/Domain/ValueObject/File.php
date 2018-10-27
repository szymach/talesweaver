<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

class File
{
    /**
     * @var object
     */
    private $value;

    public static function fromNullableValue(?object $value): ?self
    {
        if (null === $value) {
            return null;
        }

        return new self($value);
    }

    public function getValue(): object
    {
        return $this->value;
    }

    private function __construct(object $value)
    {
        $this->value = $value;
    }
}
