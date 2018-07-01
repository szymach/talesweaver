<?php

declare(strict_types=1);

namespace Application\Location\Edit;

use Application\Messages\EditionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Location;
use Domain\User;
use Application\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Location
     */
    private $location;

    public function __construct(DTO $dto, Location $location)
    {
        $this->dto = $dto;
        $this->location = $location;
    }

    public function perform(): void
    {
        $this->location->edit(
            $this->dto->getName(),
            $this->dto->getDescription(),
            $this->dto->getAvatar()
        );
    }

    public function isAllowed(User $user): bool
    {
        return $this->location->getCreatedBy()->getId() === $user->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('location');
    }
}
