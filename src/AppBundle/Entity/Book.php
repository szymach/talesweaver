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
     * @var Collection
     */
    private $chapters;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
        $this->translations = new ArrayCollection();
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
