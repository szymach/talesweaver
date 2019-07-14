<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

use Assert\Assertion;

final class LongText
{
    /**
     * @var string
     */
    private $value;

    public static function fromNullableString(?string $value): ?self
    {
        if (null === $value || true === self::isStringEmpty($value)) {
            return null;
        }

        return new self(trim($value));
    }

    public static function fromString(string $value): self
    {
        Assertion::false(self::isStringEmpty($value));

        return new self($value);
    }

    public function __toString()
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private static function isStringEmpty(string $value): bool
    {
        $strippedValue = preg_replace('/[\xc2\xa0\s]/', '', strip_tags(html_entity_decode($value)));
        return false === is_string($strippedValue) || 0 === mb_strlen($strippedValue);
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }
}
