<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Item
     */
    private $item;

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

    public function __construct(Item $item, ShortText $name, ?LongText $description, ?File $avatar)
    {
        $this->item = $item;
        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;
    }

    public function getItem(): Item
    {
        return $this->item;
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
        return $author === $this->item->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('item');
    }
}
