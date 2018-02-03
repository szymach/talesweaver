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

    public function getId(): int
    {
        return $this->id;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setItem(?Item $item): void
    {
        $this->item = $item;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }
}
