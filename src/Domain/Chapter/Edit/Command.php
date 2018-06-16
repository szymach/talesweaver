<?php

declare(strict_types=1);

namespace Domain\Chapter\Edit;

use App\Bus\Messages\EditionSuccessMessage;
use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use Domain\Entity\Chapter;
use Domain\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements MessageCommandInterface, UserAccessInterface
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

    public function getDto(): DTO
    {
        return $this->dto;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    public function isAllowed(User $user): bool
    {
        return $user->getId() === $this->chapter->getCreatedBy()->getId();
    }

    public function getMessage(): Message
    {
        return new EditionSuccessMessage('chapter');
    }
}
