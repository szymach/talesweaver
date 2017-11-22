<?php

declare(strict_types=1);

namespace Domain\Chapter\Delete;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;
use Ramsey\Uuid\UuidInterface;

class Command implements UserAccessInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var int
     */
    private $createdBy;

    public function __construct(Chapter $chapter)
    {
        $this->id = $chapter->getId();
        $this->createdBy = $chapter->getCreatedBy()->getId();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function isAllowed(User $user): bool
    {
        return $user->getId() === $this->createdBy;
    }
}
