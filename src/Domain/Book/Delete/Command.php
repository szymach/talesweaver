<?php

declare(strict_types=1);

namespace Domain\Book\Delete;

use AppBundle\Bus\Messages\DeletionSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Book;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;
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
