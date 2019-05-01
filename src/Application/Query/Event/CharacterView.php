<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Event;

use Talesweaver\Domain\Character;

final class CharacterView
{
    /**
     * @var Character
     */
    private $character;

    public function __construct(Character $character)
    {
        $this->character = $character;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }
}
