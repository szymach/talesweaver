<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Delete;

use Talesweaver\Application\Messages\DeletionSuccessMessage;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Security\AuthorAccessInterface;

final class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Chapter
     */
    private $chapter;

    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->chapter->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new DeletionSuccessMessage('chapter', ['%title%' => $this->chapter->getTitle()]);
    }

    public function isMuted(): bool
    {
        return false;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }
}
