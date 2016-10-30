<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class Scene
{
    use Traits\TranslatableTrait;

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
    private $locations;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->translations = new ArrayCollection();
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
        }
    }

    /**
     * @param Character $character
     */
    public function removeCharacter(Scene $character)
    {
        $this->characters->removeElement($character);
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
        }
    }

    /**
     * @param Location $location
     */
    public function removeLocation(Scene $location)
    {
        $this->locations->removeElement($location);
    }

    /**
     * @return Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }
}
