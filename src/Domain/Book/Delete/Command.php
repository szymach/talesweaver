<?php

namespace Domain\Book\Delete;

use AppBundle\Entity\Book;
use AppBundle\Entity\User;
use AppBundle\Security\UserAccessInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements UserAccessInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $createdBy;

    public function __construct(Book $book)
    {
        $this->id = $book->getId();
        $this->createdBy = $book->getCreatedBy()->getId();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function isAllowed(User $user) : bool
    {
        return $user->getId() === $this->createdBy;
    }
}
