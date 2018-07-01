<?php

declare(strict_types=1);

namespace Application\Character\Create;

use Application\Messages\CreationSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\User;
use Application\Security\Traits\UserAwareTrait;
use Application\Security\UserAccessInterface;
use Application\Security\UserAwareInterface;
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
