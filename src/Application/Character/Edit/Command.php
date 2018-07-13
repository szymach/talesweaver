<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Security\UserAccessInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Integration\Doctrine\Entity\User;

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
        return $user->getAuthor()->getId() === $this->character->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('character');
    }
}
