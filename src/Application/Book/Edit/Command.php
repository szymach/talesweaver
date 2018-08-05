<?php

declare(strict_types=1);

namespace Talesweaver\Application\Book\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Book
     */
    private $book;

    /**
     * @var DTO
     */
    private $data;

    public function __construct(DTO $data, Book $book)
    {
        $this->data = $data;
        $this->book = $book;
    }

    public function isAllowed(Author $author): bool
    {
        return $author->getId() === $this->book->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('book');
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getData(): DTO
    {
        return $this->data;
    }
}
