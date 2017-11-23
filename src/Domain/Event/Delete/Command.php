<?php

declare(strict_types=1);

namespace Domain\Event\Delete;

use AppBundle\Bus\Messages\DeletionSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;
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

    public function __construct(Event $event)
    {
        $this->id = $event->getId();
        $this->title = $event->getName();
        $this->createdBy = $event->getCreatedBy()->getId();
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
        return new DeletionSuccessMessage('event', ['%title%' => $this->title]);
    }
}
