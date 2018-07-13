<?php

declare(strict_types=1);

namespace Talesweaver\Application\Chapter\Edit;

use Talesweaver\Application\Messages\EditionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Security\UserAccessInterface;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Doctrine\Entity\User;

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
