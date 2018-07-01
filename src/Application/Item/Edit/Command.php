<?php

declare(strict_types=1);

namespace Application\Item\Edit;

use Application\Messages\EditionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Item;
use Domain\User;
use Application\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Item
     */
    private $item;

    public function __construct(DTO $dto, Item $item)
    {
        $this->dto = $dto;
        $this->item = $item;
    }

    public function perform(): void
    {
        $this->item->edit(
            $this->dto->getName(),
            $this->dto->getDescription(),
            $this->dto->getAvatar()
        );
    }

    public function isAllowed(User $user): bool
    {
        return $user->getId() === $this->item->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('item');
    }
}
