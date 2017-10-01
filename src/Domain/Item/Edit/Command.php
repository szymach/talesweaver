<?php

namespace Domain\Item\Edit;

use AppBundle\Entity\Item;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements UserAccessInterface
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

    public function perform() : void
    {
        $this->item->edit($this->dto);
    }

    public function isAllowed(User $user) : bool
    {
        return $user->getId() === $this->item->getCreatedBy()->getId();
    }
}
