<?php

declare(strict_types=1);

namespace Domain\Translation;

use Domain\Item;
use Domain\Traits\LocaleTrait;

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
