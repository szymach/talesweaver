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
        return $this->title;
    }

    public function edit(Edit\DTO $dto)
    {
        $this->title = $dto->getTitle();
        $this->text = $dto->getText();
        $this->chapter = $dto->getChapter();
    }

    /**
     * @return UuidInterface
     */
    public function getId() : UuidInterface
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        $this->update();
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @param string $text
     */
    public function setText(?string $text)
    {
        $this->text = $text;
        $this->update();
    }

    /**
     * @return string
     */
    public function getText() : ?string
    {
        return $this->text;
    }

    /**
     * @param Chapter|null $chapter
     */
    public function setChapter(?Chapter $chapter)
    {
        $this->chapter = $chapter;
        $this->update();
    }

    /**
     * @return Chapter|null
     */
    public function getChapter() : ?Chapter
    {
        return $this->chapter;
    }

    /**
     * @return Book|null
     */
    public function getBook() : ?Book
    {
        $book = null;
        if ($this->chapter && $this->chapter->getBook()) {
            $book = $this->chapter->getBook();
        }

        return $book;
    }

    /**
     * @param Character $character
     */
    public function addCharacter(Character $character)
    {
        if (!$this->characters->contains($character)) {
            $character->addScene($this);
            $this->characters[] = $character;
            $this->update();
        }
    }

    /**
     * @param Character $character
     */
    public function removeCharacter(Character $character)
    {
        $this->characters->removeElement($character);
        $this->update();
    }

    /**
     * @return Character[]|Collection
     */
    public function getCharacters() : Collection
    {
        return $this->characters;
    }

    /**
     * @param Location $location
     */
    public function addLocation(Location $location)
    {
        if (!$this->locations->contains($location)) {
            $location->addScene($this);
            $this->locations[] = $location;
            $this->update();
        }
    }

    /**
     * @param Location $location
     */
    public function removeLocation(Location $location)
    {
        $this->locations->removeElement($location);
        $this->update();
    }

    /**
     * @return Location[]|Collection
     */
    public function getLocations() : Collection
    {
        return $this->locations;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        if (!$this->items->contains($item)) {
            $item->addScene($this);
            $this->items[] = $item;
            $this->update();
        }
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
        $this->update();
    }

    /**
     * @return Item[]|Collection
     */
    public function getItems() : Collection
    {
        return $this->items;
    }
}
