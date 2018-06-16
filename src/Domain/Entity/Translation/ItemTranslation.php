<?php

declare(strict_types=1);

namespace Domain\Entity\Translation;

use Domain\Entity\Item;
use Domain\Entity\Traits\LocaleTrait;

class ItemTranslation
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
     * @var Item
     */
    private $item;
}
