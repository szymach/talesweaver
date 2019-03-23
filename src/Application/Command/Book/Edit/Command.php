<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Book
     */
    private $book;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var LongText|null
     */
    private $description;

    public function __construct(Book $book, ShortText $title, ?LongText $description)
    {
        $this->book = $book;
        $this->title = $title;
        $this->description = $description;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getDescription(): ?LongText
    {
        return $this->description;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->book->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('book');
    }
}
