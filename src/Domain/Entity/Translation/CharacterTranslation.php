<?php

declare(strict_types=1);

namespace Domain\Entity\Translation;

use Domain\Entity\Character;
use Domain\Entity\Traits\LocaleTrait;

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
