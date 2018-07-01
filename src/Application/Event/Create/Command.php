<?php

declare(strict_types=1);

namespace Application\Event\Create;

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
        $modelAccess = true;
        if ($this->dto->getModel() instanceof UserAccessInterface) {
            return $this->dto->getModel()->isAllowed($user);
        }

        return $user->getId() === $this->dto->getScene()->getCreatedBy()->getId()
            && $modelAccess
        ;
    }

    public function getMessage(): Message
    {
        return new CreationSuccessMessage('event', ['%title%' => $this->dto->getName()]);
    }
}
