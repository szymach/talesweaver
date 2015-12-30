<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class Chapter
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
     * @var Book
     */
    private $book;

    /**
     * @var Collection
     */
    private $scenes;

    public function __construct()
    {
        $this->scenes = new ArrayCollection();
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
     * @return Chapter
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
     * @param Scene $scene
     *
     * @return Chapter
     */
    public function addScene(Scene $scene)
    {
        if (!$this->scenes->contains($scene)) {
            $scene->setChapter($this);
            $this->scenes->add($scene);
        }

        return $this;
    }

    /**
     * @param Scene $scene
     */
    public function removeScene(Scene $scene)
    {
        $this->scenes->removeElement($scene);
        $scene->setChapter(null);
    }

    /**
     * @return Collection
     */
    public function getScenes()
    {
        return $this->scenes;
    }

    /**
     * @param Book $book
     *
     * @return Chapter
     */
    public function setBook(Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }
}
