<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\Delete;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\DeletionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\ShortText;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var Author
     */
    private $createdBy;

    public function __construct(Location $location)
    {
        $this->id = $location->getId();
        $this->title = $location->getName();
        $this->createdBy = $location->getCreatedBy();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->createdBy;
    }

    public function getMessage(): Message
    {
        return new DeletionSuccessMessage('location', ['%title%' => $this->title]);
    }
}
