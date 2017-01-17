<?php

namespace AppBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class Scene
{
    use Traits\TimestampableTrait, Traits\TranslatableTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $title;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $text;

    /**
     * @var Chapter
     */
    private $chapter;

    /**
     * @var Collection
     */
    private $characters;

    /**
     * @var Collection
     */
    private $items;

    /**
     * @var Collection
     */
    private $locations;

    public function __construct()
    {
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

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->update();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
        $this->update();
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param Chapter $chapter
     */
    public function setChapter(Chapter $chapter = null)
    {
        $this->chapter = $chapter;
        $this->update();
    }

    /**
     * @return Chapter
     */
    public function getChapter()
    {
        return $this->chapter;
    }

    /**
     * @return Book|null
     */
    public function getBook()
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
    public function removeCharacter(Scene $character)
    {
        $this->characters->removeElement($character);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getCharacters()
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
    public function removeLocation(Scene $location)
    {
        $this->locations->removeElement($location);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getLocations()
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
    public function removeItem(Scene $item)
    {
        $this->items->removeElement($item);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
