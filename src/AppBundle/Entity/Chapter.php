<?php

namespace AppBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class Chapter
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
     * @param Scene $scene
     *
     * @return Chapter
     */
    public function addScene(Scene $scene)
    {
        if (!$this->scenes->contains($scene)) {
            $scene->setChapter($this);
            $this->scenes->add($scene);
            $this->update();
        }
    }

    /**
     * @param Scene $scene
     */
    public function removeScene(Scene $scene)
    {
        $this->scenes->removeElement($scene);
        $scene->setChapter(null);
        $this->update();
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
     */
    public function setBook(Book $book = null)
    {
        $this->book = $book;
    }

    /**
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }
}
