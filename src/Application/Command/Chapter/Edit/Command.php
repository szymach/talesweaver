<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Chapter
     */
    private $chapter;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var Book|null
     */
    private $book;

    public function __construct(Chapter $chapter, ShortText $title, ?Book $book)
    {
        $this->chapter = $chapter;
        $this->title = $title;
        $this->book = $book;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->chapter->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('chapter');
    }

    public function isMuted(): bool
    {
        return false;
    }
}
