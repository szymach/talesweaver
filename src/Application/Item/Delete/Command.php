<?php

declare(strict_types=1);

namespace Talesweaver\Application\Item\Delete;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\DeletionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Security\UserAccessInterface;
use Talesweaver\Domain\Item;
use Talesweaver\Integration\Doctrine\Entity\User;

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
