<?php

namespace AppBundle\Entity\Traits;

use Doctrine\Common\Collections\Collection;

/**
 * @author Piotr Szymaszek
 */
trait TranslatableTrait
{
    use LocaleTrait;

    /**
     * @var Collection
     */
    private $translations;

    public function hasTranslation($locale)
    {
        return isset($this->translations[$locale]);
    }

    public function getTranslation($locale)
    {
        return $this->hasTranslation($locale) ? $this->translations[$locale] : null;
    }

    public function getTranslations()
    {
        return $this->translations;
    }
}
