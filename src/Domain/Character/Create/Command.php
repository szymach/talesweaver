<?php

declare(strict_types=1);

namespace Domain\Character\Create;

use App\Bus\Messages\CreationSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use App\Entity\User;
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
        return $this->dto->getScene()->getCreatedBy()->getId() === $user->getId();
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('character', ['%title%' => $this->dto->getName()]);
    }
}
