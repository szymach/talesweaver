<?php

declare(strict_types=1);

namespace Domain\Book\Edit;

use AppBundle\Bus\Messages\EditionSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Book;
use AppBundle\Entity\User;
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
