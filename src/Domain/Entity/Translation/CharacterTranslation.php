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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setCharacter(?Character $character)
    {
        $this->character = $character;
    }

    public function getCharacter(): ?Character
    {
        return $this->character;
    }
}
