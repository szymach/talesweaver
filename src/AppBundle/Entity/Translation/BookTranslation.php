<?php

namespace AppBundle\Entity\Translation;

use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

use AppBundle\Entity\Book;
use AppBundle\Entity\Traits\LocaleTrait;

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
    private $preface;

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
     *
     * @return BookTranslation
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
     * @param string $preface
     *
     * @return BookTranslation
     */
    public function setPreface($preface)
    {
        $this->preface = $preface;

        return $this;
    }

    /**
     * @return string
     */
    public function getPreface()
    {
        return $this->preface;
    }

    /**
     * @param Book $book
     *
     * @return BookTranslation
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
