<?php

declare(strict_types=1);

namespace Application\Character\Edit;

use Application\Messages\EditionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Character;
use Domain\User;
use Application\Security\UserAccessInterface;

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
        $this->character->edit(
            $this->dto->getName(),
            $this->dto->getDescription(),
            $this->dto->getAvatar()
        );
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
