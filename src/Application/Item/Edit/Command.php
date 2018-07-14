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
    private $dto;

    /**
     * @var Item
     */
    private $item;

    public function __construct(DTO $dto, Item $item)
    {
        $this->dto = $dto;
        $this->item = $item;
    }

    public function perform(): void
    {
        $this->item->edit(
            $this->dto->getName(),
            $this->dto->getDescription(),
            $this->dto->getAvatar()
        );
    }

    public function isAllowed(Author $author): bool
    {
        return $author->getId() === $this->item->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('item');
    }
}
