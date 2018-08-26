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

    public function __construct(DTO $data, Location $location)
    {
        $this->dto = $data;
        $this->location = $location;
    }

    public function isAllowed(Author $author): bool
    {
        return $this->location->getCreatedBy() === $author;
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('location');
    }

    public function getData(): DTO
    {
        return $this->dto;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
}
