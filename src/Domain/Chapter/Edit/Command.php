<?php

declare(strict_types=1);

namespace Domain\Chapter\Edit;

use AppBundle\Bus\Messages\EditionSuccessMessage;
use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\Chapter;
use AppBundle\Entity\User;
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

    public function perform(): void
    {
        $this->chapter->edit($this->dto);
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
