<?php

declare(strict_types=1);

namespace Domain\Book\Edit;

use App\Entity\Book;
use Domain\Model\IdentityTrait;

class DTO
{
    use IdentityTrait;

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
        $this->id = $book->getId();
        $this->title = $book->getTitle();
        $this->description = $book->getDescription();
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
