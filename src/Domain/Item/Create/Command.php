<?php

namespace Domain\Item\Create;

use AppBundle\Entity\User;
use Domain\Security\Traits\UserAwareTrait;
use Domain\Security\UserAccessInterface;
use Domain\Security\UserAwareInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements UserAccessInterface, UserAwareInterface
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

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getData() : DTO
    {
        return $this->dto;
    }

    public function isAllowed(User $user) : bool
    {
        return $user->getId() === $this->dto->getScene()->getCreatedBy()->getId();
    }
}