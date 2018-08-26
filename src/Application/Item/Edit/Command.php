<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var DTO
     */
    private $data;

    /**
     * @var Item
     */
    private $item;

    public function __construct(DTO $data, Item $item)
    {
        $this->data = $data;
        $this->item = $item;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->item->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('item');
    }

    public function getData(): DTO
    {
        return $this->data;
    }

    public function getItem(): Item
    {
        return $this->item;
    }
}
