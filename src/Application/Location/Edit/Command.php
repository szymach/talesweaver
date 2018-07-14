<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Command implements AuthorAccessInterface, MessageCommandInterface
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

    public function isAllowed(Author $author): bool
    {
        return $this->location->getCreatedBy()->getId() === $author->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('location');
    }
}
