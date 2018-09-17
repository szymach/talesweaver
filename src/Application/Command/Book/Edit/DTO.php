<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Edit;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Book;

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
     * @var string
     */
    private $description;

    public function __construct(Book $book)
    {
        $this->id = $book->getId();
        $this->title = (string) $book->getTitle();
        $this->description = (string) $book->getDescription();
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

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
