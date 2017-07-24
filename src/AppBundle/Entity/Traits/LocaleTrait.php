<?php

namespace AppBundle\Entity\Traits;

trait LocaleTrait
{
    /**
     * @var string
     */
    private $locale;

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
