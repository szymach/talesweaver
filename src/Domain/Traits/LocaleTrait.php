<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Traits;

trait LocaleTrait
{
    /**
     * @var string|null
     */
    private $locale;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }
}
