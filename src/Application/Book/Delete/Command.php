<?php

declare(strict_types=1);

namespace Application\Book\Delete;

use Application\Messages\DeletionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Book;
use Domain\User;
use Application\Security\UserAccessInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $createdBy;

    public function __construct(Book $book)
    {
        $this->id = $book->getId();
        $this->title = $book->getTitle();
        $this->createdBy = $book->getCreatedBy()->getId();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function isAllowed(User $user): bool
    {
        return $user->getId() === $this->createdBy;
    }

    public function getMessage(): Message
    {
        return new DeletionSuccessMessage('book', ['%title%' => $this->title]);
    }
}
