<?php

declare(strict_types=1);

namespace Domain\Location\Edit;

use AppBundle\Bus\Messages\EditionSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Location;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

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
        $this->location->edit($this->dto);
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
