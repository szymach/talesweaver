<?php

declare(strict_types=1);

namespace Domain\Book\Edit;

use App\Bus\Messages\EditionSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use App\Entity\Book;
use App\Entity\User;
use Domain\Security\UserAccessInterface;

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
        $this->book->edit($this->dto);
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
