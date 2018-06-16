<?php

declare(strict_types=1);

namespace Domain\Entity\Translation;

use Domain\Entity\Location;
use Domain\Entity\Traits\LocaleTrait;

class LocationTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Location
     */
    private $location;
}
