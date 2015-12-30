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

    public function __construct()
    {
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
     * @return SceneTranslation
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
     * @param string $text
     *
     * @return Scene
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
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
     *
     * @return Scene
     */
    public function setChapter(Chapter $chapter = null)
    {
        $this->chapter = $chapter;

        return $this;
    }

    /**
     * @return Chapter
     */
    public function getChapter()
    {
        return $this->chapter;
    }

    /**
     * @return Book
     */
    public function getBook()
    {
        $book = null;
        if ($this->chapter && $this->chapter->getBook()) {
            $book = $this->chapter->getBook();
        }

        return $book;
    }
}
