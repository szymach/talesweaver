<?php

namespace AppBundle\Entity\Traits;

trait LocaleTrait
{
    /**
     * @var string
     */
    private $locale;

    public function getLocale() : ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale) : void
    {
        $this->locale = $locale;
    }
}
