<?php

declare(strict_types=1);

namespace Domain\Translation;

use Domain\Location;
use Domain\Traits\LocaleTrait;

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
