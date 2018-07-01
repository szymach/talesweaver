<?php

declare(strict_types=1);

namespace Application\Scene\Delete;

use Application\Messages\DeletionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Scene;
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

    public function __construct(Scene $scene)
    {
        $this->id = $scene->getId();
        $this->title = $scene->getTitle();
        $this->createdBy = $scene->getCreatedBy()->getId();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function isAllowed(User $user): bool
    {
        return $this->createdBy === $user->getId();
    }

    public function getMessage(): Message
    {
        return new DeletionSuccessMessage('scene', ['%title%' => $this->title]);
    }
}
