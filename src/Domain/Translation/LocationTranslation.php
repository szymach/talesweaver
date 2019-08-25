<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Translation;

use Talesweaver\Domain\Location;
use Talesweaver\Domain\Traits\LocaleTrait;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class LocationTranslation
{
    use LocaleTrait;

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var ShortText
     */
    private $name;

    /**
     * @var LongText|null
     */
    private $description;

    /**
     * @var Location
     */
    private $location;
}
