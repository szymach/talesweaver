<?php

declare(strict_types=1);

namespace Domain\Item\Edit;

use App\Bus\Messages\EditionSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use App\Entity\Item;
use App\Entity\User;
use Domain\Security\UserAccessInterface;

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
        $this->item->edit($this->dto);
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
