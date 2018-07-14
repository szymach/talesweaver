<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Create;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Security\Traits\AuthorAwareTrait;
use Talesweaver\Domain\Security\UserAccessInterface;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Integration\Doctrine\Entity\User;

class Command implements MessageCommandInterface, UserAccessInterface, AuthorAwareInterface
{
    use AuthorAwareTrait;

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
        return $this->dto->getScene()->getCreatedBy()->getId() === $user->getAuthor()->getId();
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('character', ['%title%' => $this->dto->getName()]);
    }
}
