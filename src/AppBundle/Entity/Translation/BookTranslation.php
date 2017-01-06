<?php

namespace AppBundle\Entity\Translation;

use AppBundle\Entity\Book;
use AppBundle\Entity\Traits\LocaleTrait;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class BookTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $introduction;

    /**
     * @var string
     */
    private $expansion;

    /**
     * @var string
     */
    private $ending;

    /**
     * @var Book
     */
    private $book;

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
