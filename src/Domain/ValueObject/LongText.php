<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

class LongText
{
    /**
     * @var string
     */
    private $value;

    public static function fromNullableString(?string $value): ?self
    {
        if (null === $value) {
            return null;
        }

        if (0 === mb_strlen(preg_replace('/[\xc2\xa0\s]/', '', strip_tags(html_entity_decode($value))))) {
            return null;
        }

        return new self(trim($value));
    }

    public function __toString()
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }
}
