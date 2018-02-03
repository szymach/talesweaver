<?php

declare(strict_types=1);

namespace Domain\Character\Edit;

use App\Bus\Messages\EditionSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use Domain\Entity\Character;
use Domain\Entity\User;
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
