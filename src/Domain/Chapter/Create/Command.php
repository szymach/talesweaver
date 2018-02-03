<?php

declare(strict_types=1);

namespace Domain\Chapter\Create;

use App\Bus\Messages\CreationSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use Domain\Entity\User;
use Domain\Security\Traits\UserAwareTrait;
use Domain\Security\UserAccessInterface;
use Domain\Security\UserAwareInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements MessageCommandInterface, UserAccessInterface, UserAwareInterface
{
    use UserAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var DTO
     */
    private $dto;

    public function __construct(UuidInterface $id, DTO $dto)
    {
        $this->id = $id;
        $this->dto = $dto;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getData(): DTO
    {
        return $this->dto;
    }

    public function isAllowed(User $user): bool
    {
        $book = $this->dto->getBook();
        return !$book || $book->getCreatedBy()->getId() === $user->getId();
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('chapter', ['%title%' => $this->dto->getTitle()]);
    }
}
