<?php

namespace AppBundle\Entity;

use AppBundle\Scene\Create;
use AppBundle\Scene\Edit;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Scene
{
    use Traits\TimestampableTrait, Traits\TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var Chapter
     */
    private $chapter;

    /**
     * @var Character[]|Collection
     */
    private $characters;

    /**
     * @var Item[]|Collection
     */
    private $items;

    /**
     * @var Location[]|Collection
     */
    private $locations;

    public function __construct(UuidInterface $id, Create\DTO $dto)
    {
        $this->id = $id;
        $this->title = $dto->getTitle();
        if ($dto->getChapter()) {
            $this->chapter = $dto->getChapter();
        }

        $this->characters = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return (string) $this->title;
    }

    public function edit(Edit\DTO $dto) : void
    {
        $this->title = $dto->getTitle();
        $this->text = $dto->getText();
        $this->chapter = $dto->getChapter();
        $this->update();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getText() : ?string
    {
        return $this->text;
    }

    public function getChapter() : ?Chapter
    {
        return $this->chapter;
    }

    public function getBook() : ?Book
    {
        $book = null;
        if ($this->chapter && $this->chapter->getBook()) {
            $book = $this->chapter->getBook();
        }

        return $book;
    }

    public function addCharacter(Character $character) : void
    {
        if (!$this->characters->contains($character)) {
            $character->addScene($this);
            $this->characters[] = $character;
            $this->update();
        }
    }

    public function removeCharacter(Character $character) : void
    {
        $this->characters->removeElement($character);
        $this->update();
    }

    public function getCharacters() : Collection
    {
        return $this->characters;
    }

    public function addLocation(Location $location) : void
    {
        if (!$this->locations->contains($location)) {
            $location->addScene($this);
            $this->locations[] = $location;
            $this->update();
        }
    }

    public function removeLocation(Location $location) : void
    {
        $this->locations->removeElement($location);
        $this->update();
    }

    public function getLocations() : Collection
    {
        return $this->locations;
    }

    public function addItem(Item $item) : void
    {
        if (!$this->items->contains($item)) {
            $item->addScene($this);
            $this->items[] = $item;
            $this->update();
        }
    }

    public function removeItem(Item $item) : void
    {
        $this->items->removeElement($item);
        $this->update();
    }

    public function getItems() : Collection
    {
        return $this->items;
    }
}
