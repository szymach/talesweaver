<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Traits;

use Doctrine\Common\Collections\Collection;

trait TranslatableTrait
{
    use LocaleTrait;

    /**
     * @var Collection
     */
    private $translations;
}
