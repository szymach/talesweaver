<?php

declare(strict_types=1);

namespace Talesweaver\Application\Data;

final class Cell
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string|null
     */
    private $translationKey;

    public function __construct($value, string $translationKey = null)
    {
        $this->value = $value;
        $this->translationKey = $translationKey;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getTranslationKey(): ?string
    {
        return $this->translationKey;
    }
}
