<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class Book
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
    private $description;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $introduction;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $expansion;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $ending;

    /**
     * @var Collection
     */
    private $chapters;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
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
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $introduction
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
    }

    /**
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * @param string $expansion
     */
    public function setExpansion($expansion)
    {
        $this->expansion = $expansion;
    }

    /**
     * @return string
     */
    public function getExpansion()
    {
        return $this->expansion;
    }

    /**
     * @param string $ending
     */
    public function setEnding($ending)
    {
        $this->ending = $ending;
    }

    /**
     * @return string
     */
    public function getEnding()
    {
        return $this->ending;
    }

    /**
     * @param Chapter $chapter
     *
     * @return Book
     */
    public function addChapter(Chapter $chapter)
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setBook($this);
        }

        return $this;
    }

    /**
     * @param Chapter $chapter
     */
    public function removeChapter(Chapter $chapter)
    {
        $this->chapters->removeElement($chapter);
        $chapter->setBook(null);
    }

    /**
     * @return Collection
     */
    public function getChapters()
    {
        return $this->chapters;
    }
}
