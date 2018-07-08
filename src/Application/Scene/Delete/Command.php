<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Delete;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Messages\DeletionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Security\UserAccessInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\User;

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
