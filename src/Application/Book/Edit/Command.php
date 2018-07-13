<?php

declare(strict_types=1);

namespace Talesweaver\Application\Book\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Security\UserAccessInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Doctrine\Entity\User;

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
