<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Edit;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;

class DTO
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Book
     */
    private $book;

    public function __construct(Chapter $chapter)
    {
        $this->id = $chapter->getId();
        $this->title = (string) $chapter->getTitle();
        $this->book = $chapter->getBook();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }
}
