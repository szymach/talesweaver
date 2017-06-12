<?php

namespace AppBundle\Book\Edit;

use AppBundle\Entity\Book;

class DTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    public function __construct(Book $book)
    {
        $this->title = $book->getTitle();
        $this->description = $book->getDescription();
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }
}
