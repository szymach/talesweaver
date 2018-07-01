<?php

declare(strict_types=1);

namespace Application\Book\Edit;

use Application\Messages\EditionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Book;
use Domain\User;
use Application\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var Book
     */
    private $book;

    /**
     * @var DTO
     */
    private $dto;

    public function __construct(DTO $dto, Book $book)
    {
        $this->dto = $dto;
        $this->book = $book;
    }

    public function perform(): void
    {
        $this->book->edit($this->dto->getTitle(), $this->dto->getDescription());
    }

    public function isAllowed(User $user): bool
    {
        return $user->getId() === $this->book->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('book');
    }
}
