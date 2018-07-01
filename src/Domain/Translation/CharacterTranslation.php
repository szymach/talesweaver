<?php

declare(strict_types=1);

namespace Domain\Translation;

use Domain\Character;
use Domain\Traits\LocaleTrait;

class CharacterTranslation
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
     * @var Character
     */
    private $character;
}
