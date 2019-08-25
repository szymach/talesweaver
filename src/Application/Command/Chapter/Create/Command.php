<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Create;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Command\Security\Traits\AuthorAwareTrait;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAccessInterface, AuthorAwareInterface, MessageCommandInterface
{
    use AuthorAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var LongText|null
     */
    private $preface;

    /**
     * @var Book|null
     */
    private $book;

    public function __construct(UuidInterface $id, ShortText $title, ?LongText $preface, ?Book $book)
    {
        $this->id = $id;
        $this->title = $title;
        $this->preface = $preface;
        $this->book = $book;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getPreface(): ?LongText
    {
        return $this->preface;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @todo missing testcase
     */
    public function isAllowed(Author $author): bool
    {
        return null === $this->book || $this->book->getCreatedBy() === $author;
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('chapter', ['%title%' => $this->title]);
    }

    public function isMuted(): bool
    {
        return false;
    }
}
