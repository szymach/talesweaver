<?php

declare(strict_types=1);

namespace Application\Item\Delete;

use Application\Messages\DeletionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Item;
use Domain\User;
use Application\Security\UserAccessInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements MessageCommandInterface, UserAccessInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $createdBy;

    public function __construct(Item $item)
    {
        $this->id = $item->getId();
        $this->title = $item->getName();
        $this->createdBy = $item->getCreatedBy()->getId();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function isAllowed(User $user): bool
    {
        return $user->getId() === $this->createdBy;
    }

    public function getMessage(): Message
    {
        return new DeletionSuccessMessage('item', ['%title%' => $this->title]);
    }
}
