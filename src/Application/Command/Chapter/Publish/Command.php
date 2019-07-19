<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Publish;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Domain\ValueObject\ShortText;

final class Command implements AuthorAccessInterface, MessageCommandInterface
{
    /**
     * @var Chapter
     */
    private $chapter;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var bool
     */
    private $visible;

    /**
     * @param Chapter $chapter
     * @param ShortText $title
     * @param bool $visible
     */
    public function __construct(Chapter $chapter, ShortText $title, bool $visible)
    {
        $this->chapter = $chapter;
        $this->title = $title;
        $this->visible = $visible;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function isAllowed(Author $author): bool
    {
        return $author === $this->chapter->getCreatedBy();
    }

    public function getMessage(): Message
    {
        return new Message('chapter.alert.published', ['%title%' => $this->title], Message::SUCCESS);
    }

    public function isMuted(): bool
    {
        return false;
    }
}
