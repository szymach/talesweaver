<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Translation;

use Talesweaver\Domain\Event;
use Talesweaver\Domain\Traits\LocaleTrait;

class EventTranslation
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
     * @var Event
     */
    private $event;
}
