<?php

declare(strict_types=1);

namespace Talesweaver\Application\Character\Delete;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\DeletionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
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
     * @var UuidInterface
     */
    private $createdBy;

    public function __construct(Character $character)
    {
        $this->id = $character->getId();
        $this->title = $character->getName();
        $this->createdBy = $character->getCreatedBy();
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
        return new DeletionSuccessMessage('character', ['%title%' => $this->title]);
    }
}
