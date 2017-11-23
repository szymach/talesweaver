<?php

declare(strict_types=1);

namespace Domain\Character\Edit;

use AppBundle\Bus\Messages\EditionSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Character;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
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

    public function perform(): void
    {
        $this->character->edit($this->dto);
    }

    public function isAllowed(User $user): bool
    {
        return $user->getId() === $this->character->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('character');
    }
}
