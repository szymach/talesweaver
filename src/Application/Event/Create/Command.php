<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event\Create;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\CreationSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Security\Traits\UserAwareTrait;
use Talesweaver\Application\Security\UserAccessInterface;
use Talesweaver\Application\Security\UserAwareInterface;
use Talesweaver\Domain\User;

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
