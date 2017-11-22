<?php

declare(strict_types=1);

namespace Domain\Chapter\Edit;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements UserAccessInterface
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Chapter
     */
    private $chapter;

    public function __construct(DTO $dto, Chapter $chapter)
    {
        $this->dto = $dto;
        $this->chapter = $chapter;
    }

    public function perform(): void
    {
        $this->chapter->edit($this->dto);
    }

    public function isAllowed(User $user): bool
    {
        return $user->getId() === $this->chapter->getCreatedBy()->getId();
    }
}
