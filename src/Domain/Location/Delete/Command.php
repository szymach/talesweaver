<?php

declare(strict_types=1);

namespace Domain\Location\Delete;

use App\Bus\Messages\DeletionSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use Domain\Entity\Location;
use Domain\Entity\User;
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

    public function __construct(Location $location)
    {
        $this->id = $location->getId();
        $this->title = $location->getName();
        $this->createdBy = $location->getCreatedBy()->getId();
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
        return new DeletionSuccessMessage('location', ['%title%' => $this->title]);
    }
}
