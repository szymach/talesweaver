<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Edit;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\LongText;
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
     * @var string|null
     */
    private $description;

    public function __construct(Book $book)
    {
        $this->id = $book->getId();
        $this->title = (string) $book->getTitle();
        $this->description = null !== $book->getDescription() ? (string) $book->getDescription() : null;
    }

    public function toCommand(Book $book): Command
    {
        Assertion::notNull($this->title);
        return new Command(
            $book,
            new ShortText($this->title),
            LongText::fromNullableString($this->description)
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

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
