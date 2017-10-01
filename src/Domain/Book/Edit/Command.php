<?php

namespace Domain\Book\Edit;

use AppBundle\Entity\Book;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements UserAccessInterface
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

    public function perform() : void
    {
        $this->book->edit($this->dto);
    }

    public function isAllowed(User $user) : bool
    {
        return $user->getId() === $this->book->getCreatedBy()->getId();
    }
}
