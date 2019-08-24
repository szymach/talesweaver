<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Create;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var Book|null
     */
    private $book;

    public function toCommand(UuidInterface $chapterId, ?Book $book): Command
    {
        Assertion::notNull($this->title);

        return new Command($chapterId, new ShortText($this->title), $this->book ?? $book);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
