<?php

namespace AppBundle\Book;

use AppBundle\Entity\Book;
use DomainException;

class CreateBook
{
    /**
     * @var string
     */
    private $title;

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function createBook(): Book
    {
        if (empty($this->title)) {
            throw new DomainException('Cannot create book without a title!');
        }

        return new Book($this->title);
    }
}
