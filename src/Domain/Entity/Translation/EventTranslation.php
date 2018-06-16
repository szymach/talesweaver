<?php

declare(strict_types=1);

namespace Domain\Entity\Translation;

use Domain\Entity\Traits\LocaleTrait;

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
