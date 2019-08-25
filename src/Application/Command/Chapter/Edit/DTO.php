<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Edit;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var Book|null
     */
    private $book;

    public function __construct(Chapter $chapter)
    {
        $this->id = $chapter->getId();
        $this->title = (string) $chapter->getTitle();
        $this->book = $chapter->getBook();
    }

    public function toCommand(Chapter $chapter): Command
    {
        Assertion::notNull($this->title);

        return new Command(
            $chapter,
            new ShortText($this->title),
            null,
            $this->book
        );
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
