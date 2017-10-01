<?php

namespace Domain\Character\Edit;

use AppBundle\Entity\Character;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements UserAccessInterface
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Character
     */
    private $character;

    public function __construct(DTO $dto, Character $character)
    {
        $this->dto = $dto;
        $this->character = $character;
    }

    public function perform() : void
    {
        $this->character->edit($this->dto);
    }

    public function isAllowed(User $user) : bool
    {
        return $user->getId() === $this->character->getCreatedBy()->getId();
    }
}
