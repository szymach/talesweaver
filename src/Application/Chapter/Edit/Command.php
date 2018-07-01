<?php

declare(strict_types=1);

namespace Application\Chapter\Edit;

use Application\Messages\EditionSuccessMessage;
use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\Chapter;
use Domain\User;
use Application\Security\UserAccessInterface;

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
