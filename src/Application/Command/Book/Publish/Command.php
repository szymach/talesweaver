<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Publish;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Security\AuthorAccessInterface;
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
     * @var bool
     */
    private $visible;

    /**
     * @param Book $book
     * @param ShortText $title
     * @param bool $visible
     */
    public function __construct(Book $book, ShortText $title, bool $visible)
    {
        $this->book = $book;
        $this->title = $title;
        $this->visible = $visible;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->book->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new Message('book.alert.published', ['%title%' => $this->title], Message::SUCCESS);
    }

    public function isMuted(): bool
    {
        return false;
    }
}
