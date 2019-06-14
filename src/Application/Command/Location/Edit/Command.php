<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Location
     */
    private $location;

    /**
     * @var ShortText
     */
    private $name;

    /**
     * @var LongText|null
     */
    private $description;

    /**
     * @var File|null
     */
    private $avatar;

    public function __construct(Location $location, ShortText $name, ?LongText $description, ?File $avatar)
    {
        $this->location = $location;
        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getName(): ShortText
    {
        return $this->name;
    }

    public function getDescription(): ?LongText
    {
        return $this->description;
    }

    public function getAvatar(): ?File
    {
        return $this->avatar;
    }

    public function isAllowed(Author $author): bool
    {
        return $this->location->getCreatedBy() === $author;
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('location');
    }

    public function isMuted(): bool
    {
        return false;
    }
}
